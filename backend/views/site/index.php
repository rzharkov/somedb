<?php

/* @var $this yii\web\View */

$this->title = yii::$app->name;
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Upload single file</h2>
                <?php

                use yii\widgets\ActiveForm;

                $form = ActiveForm::begin( [ 'options' => [ 'enctype' => 'multipart/form-data' ] ] )
                ?>

                <?= $form->field( $model, 'file' )->fileInput() ?>

                <?= $form->field( $model, 'id_station' )->dropDownList( $model->getAvailableStationsList() ) ?>

                <?= $form->field( $model, 'id_measurement_interval' )->dropDownList( $model->getAvailableMeasurementIntervalsList() ) ?>

                <?= $form->field( $model, 'upload_name' )->textInput() ?>

                <?= $form->field( $model, 'comment' )->textarea( ['rows' => '6'] ) ?>

                <button>Upload</button>

                <?php ActiveForm::end() ?>
            </div>
        </div>

    </div>
</div>
