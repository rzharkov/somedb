<?php

use yii\helpers\Html;
use frontend\assets\GoogleChartAsset;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ChartForm */
/* @var $data array */

$tmp = GoogleChartAsset::register($this);
var_dump( $tmp );

$this->title = 'Charts';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="chart-index" style="width: 100%; height: 100%;">
    <h1><?= Html::encode( $this->title ) ?></h1>
    <div id="test_div" style="width: 100%; height: 500px;"></div>
	<?php echo $this->render( '_search', [ 'model' => $searchModel ] ); ?>
</div>
