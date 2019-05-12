<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\assets\GoogleChartAsset;
use nex\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\ChartForm */
/* @var $data array */

GoogleChartAsset::register( $this );

$this->title = 'Charts';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="chart-index" style="width: 100%; height: 100%;">
	<h1><?= Html::encode( $this->title ) ?></h1>
	<div id="chart_div" style="width: 100%; height: 500px;"></div>
	<div class="chart-search">

			<?php $form = ActiveForm::begin(); ?>

		<div class="form-group">
					<?= Html::button( 'Draw', [ 'class' => 'btn btn-primary', 'id' => 'btn-search', 'onClick' => 'drawChart();' ] ) ?>
					<?= Html::resetButton( 'Reset', [ 'class' => 'btn btn-default' ] ) ?>
		</div>

			<?= $form->field( $model, 'id_station' )->dropDownList( $model->getAvailableStationsList() ) ?>
			<?= $form->field( $model, 'id_measurement_interval' )->dropDownList( $model->getAvailableMeasurementIntervalsList() ) ?>

			<?= $form->field( $model, 'date_from' )->widget(
				DatePicker::className(), [
				//'size' => 'sm',
				'readonly' => false,
				'placeholder' => 'date_from',
				'clientOptions' => [
					'date' => Date( '2018-07-18', time() ),
					'format' => 'YYYY-MM-DD',
					//'minDate' => '2018-07-18',
					//'maxDate' => '2018-07-19',
					'sideBySide' => false,
					'keepInvalid' => false,
					'showTodayButton' => false,
					//'useStrict' => false,
				],
			] ); ?>

			<?= $form->field( $model, 'date_to' )->widget(
				DatePicker::className(), [
				//'size' => 'sm',
				'readonly' => false,
				'placeholder' => 'date_to',
				'clientOptions' => [
					'date' => Date( '2018-07-19', time() ),
					'format' => 'YYYY-MM-DD',
					//'minDate' => '2018-07-18',
					//'maxDate' => '2018-07-20',
					'sideBySide' => false,
					'keepInvalid' => false,
					'showTodayButton' => false,
				],
			] ); ?>

			<?php ActiveForm::end(); ?>
	</div>
