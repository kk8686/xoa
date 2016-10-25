<?php
namespace xoa\assets;

class CommonAsset extends \yii\web\AssetBundle{
	public $js = [
		'assets/js/app.js',
	];
	public $css = [
		'assets/css/common.css',
	];
	public $depends = [
		'xoa\assets\Bootstrap4Asset',
	];
}