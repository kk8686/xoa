<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use xoa\assets\CommonAsset;

CommonAsset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo Html::csrfMetaTags(); ?>
    <title><?php echo Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<?php echo $content; ?>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage();
