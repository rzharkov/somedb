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
                        'actions' => [ 'index' ],
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

        //validate TODO:

        $searchModel->load( Yii::$app->request->post() );

        $data = $searchModel->GetData( Yii::$app->request->queryParams );

        Flash::AddAll( $searchModel );

        return $this->render( 'index', [
            'searchModel' => $searchModel,
            'data' => $data,
        ] );
    }

}
