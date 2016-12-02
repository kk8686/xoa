@echo off
if not exist npm (
	echo 请先解压 构建平台.rar 到当前目录
	set /p xx=
	exit
)
npm\gulp deploy