基础 - 前端开发环境搭建
===

- 文档编写：KK

- 2016.11.08

---

1. 解压**frontent/build**目录下的`构建平台.rar`到build目录，得到`frontent/build/npm`和`frontent/build/node_modules`这些路径就对了

2. 然后直接运行`_持续构建.bat`

---

- 测试

	启动持续构建的时候，浏览器访问[http://127.0.0.1](http://127.0.0.1)或[http://localhost](http://localhost)即可看到网页
	
	这些网页是从`web/home`目录下读取的，但我们并不在这个目录修改代码

---

- 主要目录说明

	- `frontent/home-src`
	
		这是前台的源代码目录，里面都是HTML、CSS、JS和图片什么的
		
		修改这里的代码会构建一份到`web/home`目录下，包括添加图片文件也会同步

	- `frontent/home-mock`
	
		这是前台的模拟数据目录，模拟数据定义教程请见[返回模拟数据](http://www.kkh86.com/it/gulp-build-dev-env/guide-mock-response-data.html)
	
		后台的前端没开始搭暂时不介绍了
	
---

- `提醒`：请注意开发讨论组的通知，如果构建程序有优化，则`构建平台.rar`会有更新，所以你拉下来后要重新解压才能获得最新的构建环境