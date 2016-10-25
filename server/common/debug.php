<?php
if(function_exists('codecept_debug')){
	/**
	 * 测试开发时用的调试函数
	 * @author KK
	 * @param mixed $data 要输出的调试数据
	 * @param int $mode 调试模式
	 * 解释：11=输出调试数据并停止运行
	 * 
	 * @example
	 * 
	 * ```php
	 * debug(123);
	 * debug([1, 2, 3, 'a' => 'b'], 11); //停止运行
	 * ```
	 */
	function debug($data, $mode = 0){
		if($mode == 11){
			echo PHP_EOL . PHP_EOL . '======debug start======' . PHP_EOL;
			print_r($data);
			echo PHP_EOL . '======debug end======' . PHP_EOL . PHP_EOL;
			exit;
		}else{
			codecept_debug('======debug start======');
			codecept_debug($data);
			codecept_debug('======debug end======');
		}
	}
	
}else{
	/**
	 * 输出调试信息
	 * @author KK
	 * @param mixed $data 要输出的调试数据
	 * @param int $mode 调试模式
	 * 解释：11=输出调试数据并停止运行，111=附加运行回溯输出并停止运行
	 * 110=附加运行回溯输出但不停止运行
	 * 
	 * @example
	 * 
	 * ```php
	 * debug(123, 110);
	 * debug([1,2,3], 111); //输出轨迹并停止运行
	 * debug([1, 2, 3, 'a' => 'b'], 11); //停止运行
	 * ```
	 */
	function debug($data, $mode = 0){
		static $debugCount = 0;
		$debugCount++;
		
		$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' || isset($_GET['_isAjax']) || isset($_POST['_isAjax']);
		
		$exception = new \Exception();
		$lastTrace = $exception->getTrace()[0];
		$file = $lastTrace['file'];
		$line = $lastTrace['line'];
		
		$fileCodes = @file($file);
		$code = '(无法获取脚本内容)';
		if($fileCodes != ''){
			$matchedCodes = [];
			$lineScript = $fileCodes[$line - 1];
			if(preg_match('/debug.*\(.*\)(?= *;)/i', $lineScript, $matchedCodes)){
				$code = $matchedCodes[0];
			}
		}
		
		$showData = var_export($data, true);
		
		if($isAjax){
			header('Content-type:application/json;charset=utf-8');
			exit(json_encode(array(
				'file' => $file,
				'line' => $line,
				'dataStr' => $showData,
				'data' => $data,
			)));
		}else{
			$dataType = gettype($data);
			$backLink = '';
			if(isset($_SERVER['HTTP_REFERER'])){
				$backLink = '<a href="' . $_SERVER['HTTP_REFERER'] . '" style="margin-left:20px;">返回(清空表单)</a>'
							. '<br /><br style="clear:both;" /><br /><a href="javascript:history.back()" onclick="">返回(保留表单状态)</a>';
			}else{
				$backLink = '<a href="javascript:history.back()" style="margin-left:20px;">返回</a>';
			}
			
			$length = '无';
			if(is_string($data)){
				$length = strlen($data);
			}
			
			$traceHtml = '';
			if($mode == 111 || $mode == 110){
				$traceHtml = '<div><p>运行轨迹:</p><pre>' . $exception->getTraceAsString() . '</pre></div>';
			}
			
			echo <<<EOL
<div style="min-width:590px; margin:20px; padding:10px; font-size:14px; border:1px solid #000;">================= 新的调试点： 
	<span style="color:#121E31; font-size:14px;">$debugCount</span> ========================<br />
	<font style="color:green; font-size:14px;">$file</font> 第 $line 行<br />
	<font style="color:red; font-size:14px;">$code</font><br />
	调试输出内容:<br />
	类型：$dataType<br />
	字符串长度：$length<br />
	值:<br />
	<pre style="font-size:14px;"><p style="background:#92E287;">$showData</p></pre>
	$backLink
	<a href="javascript:location.reload()" style="margin-left:20px;">刷新</a>
	$traceHtml
</div>
EOL;
		}

		in_array($mode, [11, 111]) && exit();
	}
}