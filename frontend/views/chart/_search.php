<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use nex\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ChartForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chart-search">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field( $model, 'date_from' )->widget(
		DatePicker::className(), [
		'value' => '18.07.2018',
		//'defaultDate' => '2018-07-18',
		'language' => 'ru',
		'size' => 'sm',
		'readonly' => false,
		'placeholder' => 'date_from',
		'clientOptions' => [
			'format' => 'L',
			'minDate' => '2018-07-18',
			'maxDate' => '2018-07-19',
			'sideBySide' => false,
			'keepInvalid' => false,
			'showTodayButton' => false,
			//'useStrict' => false,
		],
	] ); ?>

	<?= $form->field( $model, 'date_to' )->widget(
		DatePicker::className(), [
		'value' => '19.07.2018',
		//'defaultDate' => '2018-07-19',
		'language' => 'ru',
		'size' => 'sm',
		'readonly' => false,
		'placeholder' => 'date_to',
		'clientOptions' => [
			'format' => 'L',
			'minDate' => '2018-07-18',
			'maxDate' => '2018-07-20',
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
