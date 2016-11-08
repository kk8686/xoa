基础 - 测试数据
===

- 文档编写：KK

- 2016.11.08

---

如果更新了表结构，运行单元测试之前，一定要用tools的选项2执行过模拟数据才可以，这样会产生一份数据库镜像到`server/tests/codeception/_data/dump.sql`

因为测试程序用的数据库并不是**server/data/database.db**而是**server/data/database-test.db**，每次运行测试时，测试框架都会将dump.sql向database-test.db里覆盖以便重构数据库状态来测试数据

所以dump.sql必须保持最新才方便测试，要产生dump.sql就要用tools的选项2来构造模拟数据

---

通过所有mock方法可以看到模拟的数据，其中员工账号有`12@12.com`以及`13@12.com`，mock数据后面有注释说明用处

所有密码统一为`121212`