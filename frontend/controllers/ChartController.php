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
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new ChartForm();

		$searchModel->validate();

		$searchModel->load( Yii::$app->request->post() );

		$data = $searchModel->GetData();

		Flash::AddAll( $searchModel );

		return $this->render( 'index', [
			'searchModel' => $searchModel,
			'data' => $data,
		] );
	}

	public function actionGetdata() {
		$searchModel = new ChartForm();

		$searchModel->load( Yii::$app->request->post() );
		var_dump( Yii::$app->request->post() );
		var_dump( $searchModel->validate() );
		die();
		if ( $searchModel->validate() ) {
			$data = $searchModel->GetData();
			return json_encode( $data );
		} else {
			return false;
		}
	}

}
