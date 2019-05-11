<?php

use yii\helpers\Html;
use frontend\assets\GoogleChartAsset;
use nex\datepicker\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ChartForm */
/* @var $data array */

GoogleChartAsset::register($this);

$this->title = 'Charts';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="chart-index" style="width: 100%; height: 100%;">
    <h1><?= Html::encode( $this->title ) ?></h1>
    <div id="chart_div" style="width: 100%; height: 500px;"></div>
	<?= Html::button( 'Draw', [ 'class' => 'btn btn-primary', 'id' => 'btn-search', 'onClick' => 'drawChart();' ] ) ?>
	<?php echo $this->render( '_search', [ 'model' => $searchModel ] ); ?>
</div>
