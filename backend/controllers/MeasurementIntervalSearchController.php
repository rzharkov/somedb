<?php

namespace backend\controllers;

use code\helpers\Flash;
use Yii;
use common\models\MeasurementInterval;
use backend\models\MeasurementIntervalSearchForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MeasurementIntervalearchController implements the CRUD actions for MeasurementInterval model.
 */
class MeasurementIntervalSearchController extends Controller {
	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => [ 'index', 'view' ],
						'allow' => true,
						'roles' => [ 'viewAdminPage' ],
					],
					[
						'actions' => [ 'create', 'update', 'delete' ],
						'allow' => true,
						'roles' => [ 'admin' ],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => [ 'POST' ],
				],
			],
		];
	}

	/**
	 * Lists all MeasurementInterval models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new MeasurementIntervalSearchForm();
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		Flash::AddAll( $searchModel );

		return $this->render( 'index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		] );
	}

	/**
	 * Displays a single MeasurementInterval model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView( $id ) {
		$model = $this->findModel( $id );

		Flash::AddAll( $model );

		return $this->render( 'view', [
			'model' => $this->findModel( $id ),
		] );
	}

	/**
	 * Creates a new MeasurementInterval model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new MeasurementIntervalSearchForm();

		if ( $model->load( Yii::$app->request->post() ) ) {
			$id_measurement_interval = $model->createMeasurementInterval();
			if ( $id_measurement_interval !== false ) {
				return $this->redirect( [ 'index' ] );
			}
		}

		Flash::AddAll( $model );

		return $this->render( 'create', [
			'model' => $model,
		] );
	}

	/**
	 * Updates an existing MeasurementInterval model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate( $id ) {
		$model = $this->findModel( $id );

		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect( [ 'index' ] );
		} else {
			Flash::AddAll( $model );
			return $this->render( 'update', [
				'model' => $model,
			] );
		}
	}

	/**
	 * Deletes an existing MeasurementInterval model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete( $id ) {
		$model = new MeasurementIntervalSearchForm( $id );

		if ( $model->deleteMeasurementInterval( $id ) ) {
			return $this->redirect( [ 'index' ] );
		} else {
			Flash::AddAll( $model );
			return $this->render( 'view', [
				'model' => $model,
			] );
		}
	}

	/**
	 * Finds the MeasurementInterval model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return MeasurementInterval the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id ) {
		if ( ( $model = MeasurementInterval::findOne( $id ) ) !== null ) {
			return $model;
		}

		throw new NotFoundHttpException( 'The requested page does not exist.' );
	}
}
