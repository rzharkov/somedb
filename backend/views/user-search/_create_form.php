<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSearchForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field( $model, 'email' )->textInput() ?>
    <?= $form->field( $model, 'username' )->textInput() ?>
    <?= $form->field( $model, 'new_password' )->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton( 'Save', [ 'class' => 'btn btn-success' ] ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
