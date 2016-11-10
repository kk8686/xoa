<?php
Yii::$app->off(\yii\web\Application::EVENT_BEFORE_ACTION); //去掉登陆校验，以清除对控制器的测试干扰