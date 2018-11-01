<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MeasurementInterval */

$this->title = 'Create Measurement Interval';
$this->params['breadcrumbs'][] = ['label' => 'Measurement Intervals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="measurement-interval-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_create_form', [
        'model' => $model,
    ]) ?>

</div>
