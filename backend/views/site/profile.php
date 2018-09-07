<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\ProfileForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Profile';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="site-profile">
    <h3><?= Html::encode( 'Здесь можно поменять пароль' ) ?></h3>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin( [ 'id' => 'profile-form' ] ); ?>

            <?= $form->field( $model, 'username' )->textInput( [ 'readonly' => true ] ) ?>

            <?= $form->field( $model, 'email' )->textInput( [ 'readonly' => true ] ) ?>

            <?= $form->field( $model, 'password' )->passwordInput() ?>

            <?= $form->field( $model, 'new_password' )->passwordInput() ?>

            <?= $form->field( $model, 'retype_new_password' )->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton( 'Save', [ 'class' => 'btn btn-primary', 'name' => 'save-button' ] ) ?>
                <?= Html::resetButton( 'Cancel', [ 'class' => 'btn btn-secondary', 'name' => 'cancel-button' ] ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
