<?php

namespace frontend\controllers;

use code\helpers\Flash;
use Yii;
use frontend\models\ChartForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ChartController extends Controller {
	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => [ 'index', 'getdata' ],
						'allow' => true,
						'roles' => [ 'viewAdminPage' ],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
				],
			],
		];
	}

	/**
	 * @return string
	 * @throws \Throwable
	 */
	public function actionIndex() {
		$model = new ChartForm();

		$model->load( Yii::$app->request->post() );

		$model->validate();

		$data = $model->GetData();

		Flash::AddAll( $model );

		return $this->render( 'index', [
			'model' => $model,
			'data' => $data,
		] );
	}

	/**
	 * Returns a measurements data in the json format
	 * @return bool|false|string
	 * @throws \Throwable
	 */
	public function actionGetdata() {
		$model = new ChartForm();

		$model->load( Yii::$app->request->post() );

		if ( $model->validate() ) {
			$data = $model->GetData();
			return json_encode( $data );
		} else {
			return false;
		}
	}

}
