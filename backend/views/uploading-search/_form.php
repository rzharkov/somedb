<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UploadingSearchForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uploading-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field( $model, 'name' )->textInput() ?>

	<?= $form->field( $model, 'comment' )->textInput() ?>

    <div class="form-group">
			<?= Html::submitButton( 'Save', [ 'class' => 'btn btn-success' ] ) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
