<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\StationType */

$this->title = $model->name;
$this->params[ 'breadcrumbs' ][] = [ 'label' => 'Station Types', 'url' => [ 'index' ] ];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="station-type-view">

    <h1><?= Html::encode( $this->title ) ?></h1>

    <p>
        <?= Html::a( 'Update', [ 'update', 'id' => $model->id ], [ 'class' => 'btn btn-primary' ] ) ?>
        <?= Html::a( 'Delete', [ 'delete', 'id' => $model->id ], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ] ) ?>
    </p>

    <?= DetailView::widget( [
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'status',
            [   'attribute' => 'data_format',
                'value' => function ( $model ) {
                $data_format = $model['data_format'];
                $data_format_text = "";
                if ( $data_format ) {
                    foreach ( $data_format as $key => $row ) {
                        $data_format_text .= $key . "\n";
                        foreach ( $row as $Field => $Format ) {
                            $data_format_text .= $Field . ( strlen( $Field ) > 4 ? "\t" : "\t\t" ) . $Format . "\n";
                        }
                        $data_format_text .= "\n";
                    }
                }
                return "<pre>" . $data_format_text . "</pre>";
            },
            'format' => 'raw'
            ],
            'measurements_table_name',
            'crtime',
        ],
    ] ) ?>

</div>
