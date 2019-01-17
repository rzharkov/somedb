<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Uploading */

$this->title = $model->id . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Uploadings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uploading-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'filename',
            'station_name',
            'measurement_interval_name',
            'crtime',
        ],
    ]) ?>

</div>
