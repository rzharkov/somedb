<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UploadingSearchForm */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Update Uploading: ' . $model->name;
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Uploadings', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => $model->name, 'url' => [ 'view', 'id' => $model->id ] ];
$this->params[ 'breadcrumbs' ][] = 'Update';
?>

<div class="uploading-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field( $model, 'id' )->textInput( [ 'readonly' => true ] ) ?>

    <?= $form->field( $model, 'filename' )->textInput( [ 'readonly' => true ] ) ?>

    <?= $form->field( $model, 'name' )->textInput() ?>

    <?= $form->field( $model, 'id_station' )->dropDownList( $model->getAvailableStationsList() ) ?>

    <?= $form->field( $model, 'id_measurement_interval' )->dropDownList( $model->getAvailableMeasurementIntervalsList() ) ?>

    <?= $form->field( $model, 'comment' )->textarea( [ 'rows' => '6' ] ) ?>

    <div class="form-group">
        <?= Html::submitButton( 'Save', [ 'class' => 'btn btn-success' ] ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
