<?php
namespace xoa\common\ext\log;

use Yii;
use yii\log\Logger;
use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;

class FileLog extends \yii\log\FileTarget
{
    public function export(){
		array_pop($this->messages);
        $text = implode("\n", array_map([$this, 'formatMessage'], $this->messages)) . "\n";

        if (($fp = @fopen($this->logFile, 'a')) === false) {
            throw new InvalidConfigException("Unable to append to log file: {$this->logFile}");
        }

        @flock($fp, LOCK_EX);
        if ($this->enableRotation) {
            // clear stat cache to ensure getting the real current file size and not a cached one
            // this may result in rotating twice when cached file size is used on subsequent calls
            clearstatcache();
        }
        if ($this->enableRotation && @filesize($this->logFile) > $this->maxFileSize * 1024) {
            $this->rotateFiles();
            @flock($fp, LOCK_UN);
            @fclose($fp);
            @file_put_contents($this->logFile, $text, FILE_APPEND | LOCK_EX);
        } else {
            @fwrite($fp, $text);
            @flock($fp, LOCK_UN);
            @fclose($fp);
        }
        if ($this->fileMode !== null) {
            @chmod($this->logFile, $this->fileMode);
        }
    }

    /**
     * Formats a log message for display as a string.
     * @param array $message the log message to be formatted.
     * The message structure follows that in [[Logger::messages]].
     * @return string the formatted message
     */
    public function formatMessage($message)
    {
        list($text, $level, $category, $timetamp) = $message;
        $level = Logger::getLevelName($level);
        if (!is_string($text)) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ($text instanceof \Throwable || $text instanceof \Exception) {
                $content = (string) $text;
            } else {
                $content = VarDumper::export($text);
            }
		}else{
			$content = $text;
		}
		
        $traces = [];
        if (isset($message[4])) {
            foreach ($message[4] as $trace) {
                $traces[] = "in {$trace['file']}:{$trace['line']}";
            }
        }
		$traces = implode(PHP_EOL, $traces);
		
		$exception = new \Exception();
        $ip = Yii::$app->request->userIP;
		$time = date('Y-m-d H:i:s', $timetamp);
		$file = $exception->getFile();
		$line = $exception->getLine();
		$url = Yii::$app->request->getHostInfo() . Yii::$app->request->getUrl();
		//$route = Yii::$app->request->requestedRoute;
		$route = Yii::$app->requestedAction;
		
		$trace = '';
		if(!strstr($content, 'Stack trace')){
			$trace = PHP_EOL . PHP_EOL . 'trace:' . PHP_EOL . $exception->getTraceAsString();
		}
		
		$evnVars = '';
		if(isset($_COOKIE)){
			$evnVars .= '$_COOKIES = ' . var_export($_COOKIE, true) . PHP_EOL . PHP_EOL;
		}

        //session
		if(isset($_SESSION)){
			$evnVars .= '$_SESSION = ' . var_export($_SESSION, true) . PHP_EOL . PHP_EOL;
		}
		
		if(isset($_SERVER)){
			//server
			$host = $_SERVER['HTTP_HOST'] ?? '';
			$uri = $_SERVER['REQUEST_URI'] ?? '';
			$referer = $_SERVER['HTTP_REFERER'] ?? '';
			$method = $_SERVER['REQUEST_METHOD'] ?? '';
			$agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
			$evnVars .= '$_SERVER = [' . "\n\t" .
				"'HTTP_HOST' => '" . $host  . "',\n\t" .
				"'REQUEST_URI' => '" . $uri  . "',\n\t" .
				"'HTTP_REFERER' => '" . $referer  . "',\n\t" .
				"'REQUEST_METHOD' => '" . $method  . "',\n\t" .
				"'HTTP_USER_AGENT' => '" . $agent . "',\n" . ']';

			$requestUrl = 'http://' . $host . $uri;
		}
		
		return <<<EOL
=======================LOG_START=======================
time=$time
ip=$ip
level=$level
category=$category
file=$file
line=$line
url:$url
route:$route

::message_start
$content
::message_end
$traces

Trace:
$trace

env_vars:
$evnVars
=======================LOG_END=======================
EOL;
    }

}