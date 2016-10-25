var gulp = require('gulp'),
	watch = require('gulp-watch'),
	minjs = require('gulp-uglify'),
	mincss = require('gulp-mini-css'),
	sass = require('gulp-sass'),
	mockServer = require('mock-server'),
	path = require('path'),
	fs = require('fs');


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
	gulp.task('default', ['minjs', 'mincss', 'layout', 'assets', 'mock']);

	//同步图片、字体、图标等
	gulp.task('assets', function(){
		watch(config.src + '/**/**.{jpg,jpeg,png,bmp,gif,woff,ttf,map,ico}', function(file){
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
			var stream = gulp.src(file, srcOptions);
			if(path.extname(file) == '.scss'){
				stream = stream.pipe(sass().on('error', sass.logError));
			}

			stream.pipe(mincss())
				.pipe(gulp.dest(config.dist));
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
				.pipe(gulp.dest(config.dist));
		};

		watch(config.src + '/**/**.html')
			.on('add', buildHtml)
			.on('change', buildHtml)
			.on('unlink', unlinkDistFile);
	});

	gulp.task('mock', function(){
		mockServer({
			webPath : config.dist,
			dataPath : config.mockData
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
		gulp.src(config.src + '/**/**.html')
			.pipe(layout.run())
			.pipe(gulp.dest(config.dist));
	});
	
	function unlinkDistFile(file){
		console.log(file + ' 删除');
		var distFile = config.dist + '/' + path.relative(config.src, file); //计算相对路径
		fs.existsSync(distFile) && fs.unlink(distFile);
	}
}

//从控制台获取输入的项目ID
function getBuildProjectId(){
	console.log('1. 前台\n\
2. 后台\n\
请输入要构建的项目ID：');
	process.stdin.pause();  
	var response = fs.readSync(process.stdin.fd, 1000, 0, 'utf8');  
	process.stdin.end();
	return response[0].trim();  
}