<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MeasurementInterval */

$this->title = 'Update Measurement Interval: ' . $model->name;
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Measurement Intervals', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => $model->name, 'url' => [ 'view', 'id' => $model->id ] ];
$this->params[ 'breadcrumbs' ][] = 'Update';
?>
<div class="measurement-interval-update">

    <h1><?= Html::encode( $this->title ) ?></h1>

	<?= $this->render( '_form', [
		'model' => $model,
	] ) ?>

</div>
