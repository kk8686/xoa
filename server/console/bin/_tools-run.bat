@echo off

:start
cls
set clearDb=1
set buildDbWithMock=2
set buildDbOnly=3
set checkRequirements=4
set exit=x
echo.
echo  %clearDb%.清空数据库
echo  %buildDbWithMock%.构造数据库+模拟数据（开发时用）
echo  %buildDbOnly%.仅构造数据库的必要结构和数据（线上部署时用）
echo  %checkRequirements%.检测Yii框架支持情况
echo  %exit%.退出
echo.
echo.

set /p want=请输入选项数字：
set command=

if %want%==%clearDb% (
	set command=%~dp0\yii migrate/down all
)
if %want%==%buildDbWithMock% (
	set command=%~dp0\yii migrate/up all --mockData=1 --exportFile=@
)
if %want%==%buildDbOnly% (
	set command=%~dp0\yii migrate/up all
)
if %want%==%checkRequirements% (
	set command=php %~dp0\requirements.php
)
if %want%==%exit% (
	exit
)

%command%

echo 居然运行不到这里，一直未能解决，求高人
pause

echo .
if errorLevel 1 (
	set /p tmp = 执行失败，回车返回上一级
) else (
	set /p tmp = 执行成功，回车返回上一级
)
goto start