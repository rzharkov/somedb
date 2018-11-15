<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\StationSearchForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="station-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'id_type') ?>

    <?= $form->field($model, 'timezone') ?>

    <?= $form->field($model, 'address') ?>

    <?= $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'crtime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
