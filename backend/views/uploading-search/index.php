<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UploadingSearchForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Uploadings';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="uploading-index">

    <h1><?= Html::encode( $this->title ) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget( [
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'filename',
            'comment',
            'crtime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ],
        'rowOptions' => function ( $model ) {
            if ( $model->status === 3 ) {
                return [ 'class' => 'danger' ];
            }
        }
    ] ); ?>
</div>
