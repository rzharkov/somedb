<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class GoogleChartAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'https://www.gstatic.com/charts/loader.js',
		'https://www.google.com/jsapi',
		'js/charts.js'
	];
	public $depends = [
	];
}
