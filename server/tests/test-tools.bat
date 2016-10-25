@echo off
goto navi
set unitTestName=
pause
exit
:navi
cls
echo.
echo.
echo   hi,这个测试小工具是为了免除你老是手动敲命令而制作的哦^^^-^^
echo.
echo.  ┏━━━━━━━━━━━━━━━━━━━━┓
echo.  ┃              测试小工具                ┃
echo.  ┣━━━━━━━━━━━━━━━━━━━━┫
echo.  ┃  1.运行单元测试                        ┃
echo.  ┃  2.运行全部测试                        ┃
echo.  ┃  3.创建单元测试用例                    ┃
echo.  ┃  4.创建验收测试用例                    ┃
echo.  ┃  5.运行验收测试(不可用,没写完脚本)     ┃
echo.  ┃                                        ┃
echo.  ┃  x.退出                                ┃
echo.  ┗━━━━━━━━━━━━━━━━━━━━┛
echo.

set menuId=0
set /p menuId=请输入菜单序号:

if "%menuId%"=="x" (exit)
if %menuId%==1 goto runUnitTest
if %menuId%==2 goto runAllTest
if %menuId%==3 goto createUnitTest
if %menuId%==4 goto createCeptTest
goto navi

::=============运行单元测试s=================
:runUnitTest
echo.
echo.  ┏━━━━━━━━━━━━━━━━━━━━┓
echo.  ┃              单元测试选项              ┃
echo.  ┣━━━━━━━━━━━━━━━━━━━━┫
echo.  ┃  1.运行全部单元单元测试                ┃
echo.  ┃  2.运行指定测试用例                    ┃
echo.  ┃  3.运行指定测试用例的指定方法          ┃
echo.  ┃                                        ┃
echo.  ┃  -.(减号)返回上一层                    ┃
echo.  ┃  x.退出                                ┃
echo.  ┗━━━━━━━━━━━━━━━━━━━━┛
echo.

set unitMenuId=0
set /p unitMenuId=请输入菜单序号:
if "%unitMenuId%"=="-" (
    goto navi
)

if "%unitMenuId%"=="x" exit
if %unitMenuId%==1 (
    cls
    %codecept% run unit
    echo.
    echo ==============以上是 全部单元测试 的测试结果报告=================
    goto runUnitTest
)
if %unitMenuId%==2 (
goto runSomeUnitTest)
goto runUnitTest
::=============运行单元测试e=================


::=============运行所有测试s=================
:runAllTest
cls
%codecept% run
echo.
echo ==============以上是 全部测试 的测试结果报告=================
goto navi
::=============运行所有测试e=================


::=============运行指定单元测试用例s=================
:runSomeUnitTest
echo.

set someUnitTestMenuId=0
if "%unitTestName%"=="" (
    cls
    goto readyToRunSomeUnitTest
) else (
    echo.  ┏━━━━━━━━━━━━━━━━━━━━┓
    echo.  ┃          运行指定单元测试用例          ┃
    echo.  ┣━━━━━━━━━━━━━━━━━━━━
    echo.  ┃  1.继续上一次 %unitTestName% 的测试
    echo.  ┃  2.输入用例名称并运行                  
    echo.  ┃                                        
    echo.  ┃  -.（减号）返回上一层                  
    echo.  ┃  x.退出                                
    echo.  ┗━━━━━━━━━━━━━━━━━━━━┛

    set /p someUnitTestMenuId=请输入菜单序号:
)
if "%someUnitTestMenuId%"=="x" exit
if %someUnitTestMenuId%==1 (
    cls
    %codecept% run unit %unitTestName%
    goto runSomeUnitTest
)

if %someUnitTestMenuId%==2 (
cls
:readyToRunSomeUnitTest
    echo 下面请输入测试用例名称,可以输入分组目录,比如 model\ModelDbTest
    echo 输入-（减号）返回上一层
    ::echo 输入之前是%unitTestName%
    set unitTestName=211111
    set /p unitTestName=
    ::echo 输入之后是%unitTestName%
    pause
    if "%unitTestName%"=="x" exit
    if "%unitTestName%"=="-" (
        set unitTestName=
        goto runUnitTest
    )
    
    if %unitTestName%=="" (
        echo 您没有输入测试用例的名称
        goto runSomeUnitTest
    )
    %codecept% run unit %unitTestName%
    echo.
    echo ====以上是 单元测试用例 %unitTestName% 的测试结果报告====
    goto runSomeUnitTest
)

goto runSomeUnitTest
::=============运行指定单元测试用例e=================

::=============创建单元测试用例s=================
:createUnitTest
cls
echo.
echo  准备创建单元测试用例
echo  输入 User 会在unit目录下产生 UserTest.php
echo  如果要分组,直接在前面加目录,如 product/Phone ,则前面product部分会被创建相应目录
echo  -.返回上一层
echo.
set /p unitTestName=请输入测试用例的名称:
if "%unitTestName%"=="" (
	goto createUnitTest
)
if %unitTestName%==x goto navi
%codecept% generate:test unit %unitTestName%
pause
goto navi
::=============创建单元测试用例e=================


::=============创建验收测试用例s=================
:createCeptTest
cls
echo.
echo  准备创建验收测试用例
echo  输入 Login 会在acceptance目录下产生 LoginCept.php
echo  如果要分组,直接在前面加目录,如 product/Detail ,则前面product部分会被创建相应目录
echo  -.返回上一层
echo.
set /p ceptTestName=请输入测试用例的名称:
if "%ceptTestName%"=="" (
	goto createCeptTest
)
if %ceptTestName%==x goto navi
%codecept% generate:cept acceptance %ceptTestName%
pause
goto navi
::=============创建验收测试用例e=================