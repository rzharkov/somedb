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
    <div id="test_div" style="width: 900px; height: 500px;"></div>
    <script>
        var data = google.visualization.arrayToDataTable([
            ['Country', 'Popularity'],
            ['Sweden', 300],
            ['United States', 300],
            ['France', 400],
            ['Canada', 500],
            ['Spain', 500],
            ['RU', 900]
        ]);
        var options = {
            title: 'Simple map' // Заголовок.
        };
        var chart = new google.visualization.GeoChart(document.getElementById('test_div'));
        chart.draw(data, options);
    </script>
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
	<?php echo $this->render( '_search', [ 'model' => $searchModel ] ); ?>
</div>
