<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StationType */

$this->title = 'Update Station Type: ' . $model->name;
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Station Types', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = [ 'label' => $model->name, 'url' => [ 'view', 'id' => $model->id ] ];
$this->params[ 'breadcrumbs' ][] = 'Update';
?>
<div class="station-type-update">

    <h1><?= Html::encode( $this->title ) ?></h1>

	<?= $this->render( '_form', [
		'model' => $model,
	] ) ?>

</div>
