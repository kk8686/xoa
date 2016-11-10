基础 - 技术介绍
===

- 文档编写：KK

- 2016.11.08

---

主要编程语言**：PHP7** （提示：下载phpStudy最新版可以切换到PHP7）

程序框架：**[Yii2](https://github.com/yiisoft/yii2)**

数据库：**[SQLite3](http://www.runoob.com/sqlite/sqlite-tutorial.html)**（*熟悉Yii的话可以快速改成MySQL，未来会出修改指引*）

单元测试框架：**[Codeception](http://www.kkh86.com/it/codeception/guide-README.html)**

Web服务器：**不限，但自带.htaccess，用Nginx请自主定义相关重写规则

文档编写语言：**[Markdown](http://www.kkh86.com/it/markdown/guide-README.html)**

运行平台：**Windows/Linux**

前端UI：**暂时用[Bootstrap3](http://v3.bootcss.com/css)，最终是想用Bootstrap4的**

样式语言：**[SASS](http://www.w3cplus.com/sassguide)**

前端构建平台：**[Gulp](http://www.kkh86.com/it/gulp-build-dev-env/guide-README.html)**

---

一些工具用了bat脚本，以后会考虑用C#做可视化版本的工具

前端目前随便写的，还没整理，也没有模块化开发，晚点，晚点吧……谁有空整理前端？

- 未来规划：

	还打算用React Native做两平台终端版本，而消息也是实时监听的，涉及WebSocket，不排除会考虑加入`Workerman`框架，到时候这个项目可能是双框架共同运作业务的项目

	Yii2的本地化做得挺好，我以前已经用它搞过2个双框架项目，甚至三框架也试验过，说到底其实全局的东西不冲突就行
	
	其实基于Codeception框架下引入Yii2框架跑测试代码已经是双框架了，再算上Codeception底层还用了PHPUnit、Symfony和Guzzle框架，没事的，一个项目里框架可多了 -_-
	
---
	
附录：
	
[通用代码规范](http://www.kkh86.com/it/code-standard/guide-std-global.html)

[后端代码规范](http://www.kkh86.com/it/code-standard/guide-std-php.html)

[JS代码规范](http://www.kkh86.com/it/code-standard/guide-std-js.html)

[HTML+CSS](http://www.kkh86.com/it/code-standard/guide-std-html-css.html)