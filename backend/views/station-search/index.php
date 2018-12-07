<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StationSearchForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stations';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="station-index">

    <h1><?= Html::encode( $this->title ) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a( 'Create Station', [ 'create' ], [ 'class' => 'btn btn-success' ] ) ?>
    </p>

    <?= GridView::widget( [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [ 'class' => 'yii\grid\SerialColumn' ],

            'id',
            'name',
            [
                'attribute' => 'id_type',
                'label' => 'Тип станции',
                'content' => function ( $data ) {
                    $tmp = \common\models\StationType::findOne( [ 'id' => $data[ 'id_type' ] ] );
                    $station_name = $tmp->name;

                    return $station_name;
                },
                'filter' => $searchModel->getAvailableStationTypesList(),
            ],
            'timezone',
            'address',
            'comment',
            'crtime',

            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
        'rowOptions' => function ( $model ) {
            if ( $model->status === 3 ) {
                return [ 'class' => 'danger' ];
            }
        }
    ] ); ?>
</div>
