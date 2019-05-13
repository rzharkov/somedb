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

		<?= Html::button( 'Draw', [ 'class' => 'btn btn-primary', 'id' => 'btn-search', 'onClick' => 'drawChart();' ] ) ?>
		<?= Html::resetButton( 'Reset', [ 'class' => 'btn btn-default' ] ) ?>
		<?= Html::resetButton( 'Open image in new window', [ 'class' => 'btn btn-default', 'onClick' => 'openImageInNewWindow();' ] ) ?>
		<div class="form-group">
			<div id="leftdiv" style="float: left; margin: 10px;">
				<?= $form->field( $model, 'id_station' )->dropDownList( $model->getAvailableStationsList() ) ?>
				<?= $form->field( $model, 'id_measurement_interval' )->dropDownList( $model->getAvailableMeasurementIntervalsList() ) ?>

				<?= $form->field( $model, 'date_from' )->widget(
					DatePicker::className(), [
					//'size' => 'sm',
					'readonly' => false,
					'placeholder' => 'date_from',
					'clientOptions' => [
						'format' => 'YYYY-MM-DD',
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
						'format' => 'YYYY-MM-DD',
						'sideBySide' => false,
						'keepInvalid' => false,
						'showTodayButton' => false,
					],
				] ); ?>
			</div>
			<div id="fields_list_div" style="float: left; margin: 10px;">
				<?= $form->field( $model, 'visible_fields' )->listBox(
					$model->GetVisibleFieldsList(),
					[
						'multiple' => true,
						'size' => count( $model->GetVisibleFieldsList() ),
					]
				);
				?>
			</div>
			<div id="fields_list2_div" style="float: left; margin: 10px;">
				<?= $form->field( $model, 'visible_fields2' )->listBox(
					$model->GetVisibleFieldsList(),
					[
						'multiple' => true,
						'size' => count( $model->GetVisibleFieldsList() ),
					]
				);
				?>
			</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
