var gulp = require('gulp'),
	watch = require('gulp-watch'),
	minjs = require('gulp-uglify'),
	mincss = require('gulp-mini-css'),
	sass = require('gulp-sass'),
	mockServer = require('mock-server'),
	path = require('path'),
	fs = require('fs'),
	template = require('art-template');

template.config('cache', false);
template.config('extname', '');
template.config('compress', true);

var projectId = getBuildProjectId();
var configs = require('./config');
if(!configs.projects[projectId]){
	console.error('无效的项目ID');
	return;
}
setupTasks(configs.projects[projectId]);

function setupTasks(config){
	var srcOptions = {base : config.src};

	//默认任务	
	gulp.task('default', ['minjs', 'mincss', 'layout', 'assets', 'url-rules', 'mock']);

	//同步图片、字体、图标等
	gulp.task('assets', function(){
		watch(config.src + '/**/**.{jpg,jpeg,png,bmp,gif,woff,ttf,eot,map,ico,svg}', function(file){
			gulp.src(file, srcOptions)
				.pipe(gulp.dest(config.dist));
		})
			.on('add', function(file){
				console.log(file + ' 添加');
				gulp.src(file, srcOptions)
					.pipe(gulp.dest(config.dist));
			})
			.on('change', function(file){
				console.log(file + ' 修改');
				gulp.src(file, srcOptions)
					.pipe(gulp.dest(config.dist));
			})
			.on('unlink', unlinkDistFile);
	});

	//压缩JS
	gulp.task('minjs', function(){
		var buildJs = function(file){
			console.log(file + ' 发生变动');
			gulp.src(file, srcOptions)
				.pipe(minjs().on('error', function(error){
					console.error(error.message + '\n出错行号:' + error.lineNumber);
				}))
				.pipe(gulp.dest(config.dist));
		};

		watch(config.src + '/**/**.js')
			.on('add', buildJs)
			.on('change', buildJs)
			.on('unlink', unlinkDistFile);
	});

	//压缩CSS，编译SASS
	gulp.task('mincss', function(){
		var buildCss = function(file){
			console.log(file + ' 发生变动');
			//var stream = gulp.src(file, srcOptions);
			var stream = gulp.src(config.src + '/**/**.{scss,css}', srcOptions);
			if(path.extname(file) == '.scss'){
				stream = stream.pipe(sass().on('error', sass.logError));
			}

			stream.pipe(mincss()).pipe(gulp.dest(config.dist));
		};

		watch(config.src + '/**/**.{css,scss}')
			.on('add', buildCss)
			.on('change', buildCss)
			.on('unlink', unlinkDistFile);
	});

	//合并layout
	gulp.task('layout', function(){
		var layout = require('gulp-layout');
		layout.config({
			workingPath : config.src,
			layouts : config.layouts
		});

		var buildHtml = function(file){
			gulp.src(file, srcOptions)
				.pipe(layout.run())
				.pipe(gulp.dest(config.dist))
				.on('end', function(){
					var distFile = config.dist + '/' + path.relative(config.src, file);
					var html = template(distFile, config.dict);
					fs.writeFile(distFile, html);
				});
		
		};

		watch(config.src + '/**/**.html')
			.on('add', buildHtml)
			.on('change', buildHtml)
			.on('unlink', unlinkDistFile);
	});
	
	gulp.task('url-rules', function(){
		watch(config.urlRulesFile).on('change', buildUrlRules);
	});

	gulp.task('mock', function(){
		mockServer({
			webPath : config.dist,
			dataPath : config.mockData,
			urlRulesFile : config.urlRulesFile
		});
	});
	
	gulp.task('deploy', function(){
		gulp.src(config.src + '/**/**.{jpg,jpeg,png,bmp,gif,woff,ttf,map,ico}')
			.pipe(gulp.dest(config.dist));

		gulp.src(config.src + '/**/**.js')
			.pipe(minjs().on('error', function(error){
				console.log(error.message, error.lineNumber);
			}))
			.pipe(gulp.dest(config.dist));

		gulp.src(config.src + '/**/**.css')
			.pipe(mincss())
			.pipe(gulp.dest(config.dist));
		gulp.src(config.src + '/**/**.scss')
			.pipe(sass().on('error', sass.logError))
			.pipe(gulp.dest(config.dist));

		var layout = require('gulp-layout');
		layout.config({
			workingPath : config.src,
			layouts : config.layouts
		});
		
		var through2 = require('gulp-layout/node_modules/through2');
		gulp.src(config.src + '/**/**.html')
			.pipe(layout.run())
			.pipe(gulp.dest(config.dist))
			.pipe(through2.obj(function(file, enc, cb){
				console.log('正在编译模板：' + file.path);
				var distFile = config.dist + '/' + path.relative(config.src, file.path);
				var html = template(distFile, config.dict);
				fs.writeFile(distFile, html);
				
				this.push(file);
				return cb();
			}));
	
		buildUrlRules();
	});
	
	function unlinkDistFile(file){
		console.log(file + ' 删除');
		var distFile = config.dist + '/' + path.relative(config.src, file); //计算相对路径
		fs.existsSync(distFile) && fs.unlink(distFile);
	}

	function buildUrlRules(){
		console.log('url规则文件发生变动');
		var buildToFile = config.buildUrlRulesFileName;
		delete require.cache[config.urlRulesFile];
		var urlRules = require(config.urlRulesFile);
		fs.writeFile(buildToFile, JSON.stringify(urlRules));
	}
}

//从控制台获取输入的项目ID
function getBuildProjectId(){
	console.log('1. 前台\n\
2. 后台（还没开发）\n\n\
请输入要构建的项目ID：');
	process.stdin.pause();  
	var response = fs.readSync(process.stdin.fd, 1000, 0, 'utf8');  
	process.stdin.end();
	return response[0].trim();
}