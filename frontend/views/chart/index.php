<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\assets\GoogleChartAsset;
use nex\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\ChartForm */
/* @var $data array */

GoogleChartAsset::register($this);

$this->title = 'Charts';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>

<div class="chart-index" style="width: 100%; height: 100%;">
    <h1><?= Html::encode( $this->title ) ?></h1>
    <div id="chart_div" style="width: 100%; height: 500px;"></div>
	<?= Html::button( 'Draw', [ 'class' => 'btn btn-primary', 'id' => 'btn-search', 'onClick' => 'drawChart();' ] ) ?>
    <div class="chart-search">

			<?php $form = ActiveForm::begin(); ?>

			<?= $form->field( $model, 'date_from' )->widget(
				DatePicker::className(), [
				'value' => '2018-07-18',
				//'size' => 'sm',
				'readonly' => false,
				'placeholder' => 'date_from',
				'clientOptions' => [
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
				'value' => '2018-07-19',
				//'size' => 'sm',
				'readonly' => false,
				'placeholder' => 'date_to',
				'clientOptions' => [
					'format' => 'YYYY-MM-DD',
					//'minDate' => '2018-07-18',
					//'maxDate' => '2018-07-20',
					'sideBySide' => false,
					'keepInvalid' => false,
					'showTodayButton' => false,
				],
			] ); ?>

			<?= $form->field( $model, 'id_uploading' ) ?>

        <div class="form-group">
					<?= Html::submitButton( 'Search', [ 'class' => 'btn btn-primary' ] ) ?>
					<?= Html::resetButton( 'Reset', [ 'class' => 'btn btn-default' ] ) ?>
        </div>

			<?php ActiveForm::end(); ?>
    </div>
