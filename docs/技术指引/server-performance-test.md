基础 - 运行测试用例
===

- 文档编写：KK

- 2016.11.09

---

1. 先添加一个叫codecept的系统变量，值是`php $codecept.phar的路径`，比如codecept.phar的路径是`D:\php\bin\codecept.phar`，则codecept变量的值是`php D:\php\bin\codecept.phar`

2. 确认codecept变量添加成功，重开一个cmd执行`echo %codecept%`有上述路径输出就可以

3. 测试程序目录都在server/tests里，运行里面的**test-tools.bat**可以快速执行测试，或者自己熟悉Codeception命令的话自己在cmd里敲命令执行吧