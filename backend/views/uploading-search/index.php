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
			[
				'attribute' => 'id_station',
				//'label' => 'id_station',
				'content' => function ( $data ) {
					$tmp = \common\models\Station::findOne( [ 'id' => $data[ 'id_station' ] ] );
					$station_name = $tmp->name;
					return $station_name;
				},
				'filter' => $searchModel->getAvailableStationsList(),
			],
			[
				'attribute' => 'id_measurement_interval',
				//'label' => 'id_measurement_interval',
				'content' => function ( $data ) {
					$tmp = \common\models\MeasurementInterval::findOne( [ 'id' => $data[ 'id_measurement_interval' ] ] );
					$station_name = $tmp->name;
					return $station_name;
				},
				'filter' => $searchModel->getAvailableMeasurementIntervalsList(),
			],
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
