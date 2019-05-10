<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\StationSearchForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="station-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field( $model, 'name' )->textInput() ?>

	<?= $form->field( $model, 'id_type' )->dropDownList( $model->getAvailableStationTypesList() ) ?>

	<?= $form->field( $model, 'timezone' )->textInput() ?>

	<?= $form->field( $model, 'address' )->textInput() ?>

	<?= $form->field( $model, 'comment' )->textInput() ?>

    <div class="form-group">
			<?= Html::submitButton( 'Save', [ 'class' => 'btn btn-success' ] ) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
