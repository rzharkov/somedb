<?php

namespace backend\controllers;

use code\helpers\Flash;
use Yii;
use common\models\Uploading;
use backend\models\UploadingSearchForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UploadingSearchController implements the CRUD actions for Uploading model.
 */
class UploadingSearchController extends Controller {
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
						'actions' => [ 'update', 'delete' ],
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
	 * Lists all Uploading models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new UploadingSearchForm();
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		Flash::AddAll( $searchModel );

		return $this->render( 'index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		] );
	}

	/**
	 * Displays a single Uploading model.
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
	 * Creates a new Uploading model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		//TODO: добыть и адаптировать с сайт контроллера

		$model = new UploadingSearchForm();

		if ( $model->load( Yii::$app->request->post() ) ) {
			$id_station = $model->Upload();
			if ( $id_station !== false ) {
				return $this->redirect( [ 'view', 'id' => $id_station ] );
			}
		}

		Flash::AddAll( $model );

		return $this->render( 'create', [
			'model' => $model,
		] );
	}

	/**
	 * Updates an existing Uploading model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate( $id ) {
		$model = new UploadingSearchForm( $id );
		$model->scenario = 'Update';

		if ( $model->load( Yii::$app->request->post() ) && $model->updateUploading( $id ) ) {
			return $this->redirect( [ 'view', 'id' => $model->id ] );
		} else {
			Flash::AddAll( $model );
			return $this->render( 'update', [
				'model' => $model,
			] );
		}
	}

	/**
	 * Deletes an existing Uploading model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete( $id ) {
		$model = new UploadingSearchForm( $id );

		if ( $model->deleteUploading( $id ) ) {
			return $this->redirect( [ 'index' ] );
		} else {
			Flash::AddAll( $model );
			return $this->render( 'view', [
				'model' => $model,
			] );
		}
	}

	/**
	 * Finds the Uploading model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Uploading the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id ) {
		if ( ( $model = Uploading::findById( $id ) ) !== null ) {
			return $model;
		}

		throw new NotFoundHttpException( 'The requested page does not exist.' );
	}
}
