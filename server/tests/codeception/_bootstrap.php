<?php
define('PROJECT_PATH', realpath(__DIR__ . '/../../..'));
include(PROJECT_PATH .'/server/common/AppBooter.php');
AppBooter::createApp(PROJECT_PATH . '/server/home', include(PROJECT_PATH . '/server/tests/codeception/config/config.php'))->init();