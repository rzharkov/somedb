<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MeasurementIntervalSearchForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Measurement Intervals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="measurement-interval-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Measurement Interval', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'rowOptions' => function ( $model ) {
            if ( $model->status === 3 ) {
                return [ 'class' => 'danger' ];
            }
        }
    ]); ?>
</div>
