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

							<?= $form->field( $model, 'upload_name' )->textInput() ?>

							<?= $form->field( $model, 'id_station' )->dropDownList( $model->getAvailableStationsList() ) ?>

							<?= $form->field( $model, 'id_measurement_interval' )->dropDownList( $model->getAvailableMeasurementIntervalsList() ) ?>

							<?= $form->field( $model, 'comment' )->textarea( [ 'rows' => '6' ] ) ?>

                <button>Upload</button>

							<?php ActiveForm::end() ?>
            </div>
        </div>

    </div>
</div>
<script language="JavaScript">
	document.getElementById("uploadingsearchform-file").onchange = function () {
		if (typeof document.getElementById("uploadingsearchform-file").files[0].name !== "undefined") {
			//заполним по возсожности поля для ввода
			combobox = document.getElementById("uploadingsearchform-id_measurement_interval");
			filename = document.getElementById("uploadingsearchform-file").files[0].name;
			filename_without_extension = filename.split('.').slice(0, -1).join('.');

			if (filename_without_extension.length > 0) {
				document.getElementById("uploadingsearchform-upload_name").value = filename_without_extension;
			} else {
				document.getElementById("uploadingsearchform-upload_name").value = filename;
			}

			for (var i = 0; i <= combobox.options.length - 1; i++) {
				tmp = filename.toLowerCase().indexOf(combobox.options[i].text.toLowerCase());
				if (tmp >= 0) {
					combobox.value = combobox.options[i].value;
				}
			}
		}
	};
</script>
