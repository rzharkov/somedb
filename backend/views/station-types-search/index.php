<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StationTypesSearchForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Station Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="station-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Station Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'status',
            'crtime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'rowOptions' => function ( $model ) {
            if ( $model->status === 3 ) {
                return [ 'class' => 'danger' ];
            }
        }
    ]); ?>
</div>
