<?php

use yii\helpers\Html;
use frontend\models\ChartForm;
use scotthuangzl\googlechart\GoogleChart;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ChartForm */
/* @var $data array */

$this->title = 'Charts';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="chart-index">

    <h1><?= Html::encode( $this->title ) ?></h1>
    <?php
    echo GoogleChart::widget( array( 'visualization' => 'LineChart',
        'data' => $data,
        'options' => array(
            'title' => 'Осмотическое давление. Тест',
            'titleTextStyle' => array( 'color' => '#FF0000' ),
            'hAxis' => array(
                //'title' => 'Время измерения',
                'format' => 'M/d/yy',
                'maxValue' => 'auto',
                'gridlines' => array(
                    'count' => 144,
                    'color' => 'red'  //set grid line transparent
                ),
                'minorGridlines' => [ 'color' => 'red', 'count' => 10 ]
                //'textPosition' => 'in'
            ),
            'vAxis' => array(
                'title' => 'kPa',
                //'format' => 'currency',
                'maxValue' => 'auto',
                'gridlines' => array(
                    //'count' => 10,
                    //'color' => 'black'  //set grid line transparent
                ),
            ),
            //'curveType' => 'function', //smooth curve or not
            'legend' => array( 'position' => 'right', 'color' => 'transparent' ),
        ) )
    );
    ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
