<?php

namespace backend\controllers;

use code\helpers\Flash;
use Yii;
use common\models\Station;
use backend\models\StationSearchForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StationSearchController implements the CRUD actions for Station model.
 */
class StationSearchController extends Controller {
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
     * Lists all Station models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new StationSearchForm();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );

        Flash::AddAll( $searchModel );

        return $this->render( 'index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ] );
    }

    /**
     * Displays a single Station model.
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
     * Creates a new Station model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new StationSearchForm();

        if ( $model->load( Yii::$app->request->post() ) ) {
            $id_station = $model->createStation();
            if ( $id_station !== false ) {
                return $this->redirect( [ 'view', 'id' => $id_station ] );
            }
        }

        Flash::AddAll( $model );

        return $this->render( 'create', [
            'model' => $model,
        ] );
    }

    //TODO:остановился здесь

    /**
     * Updates an existing Station model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate( $id ) {
        $model = new StationSearchForm( $id );

        if ( $model->load( Yii::$app->request->post() ) && $model->updateStation( $id ) ) {
            return $this->redirect( [ 'view', 'id' => $model->id ] );
        } else {
            Flash::AddAll( $model );
            return $this->render( 'update', [
                'model' => $model,
            ] );
        }
    }

    /**
     * Deletes an existing Station model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete( $id ) {
        $model = new StationSearchForm( $id );

        if ( $model->deleteStation( $id ) ) {
            return $this->redirect( [ 'index' ] );
        } else {
            Flash::AddAll( $model );
            return $this->render( 'view', [
                'model' => $model,
            ] );
        }
    }

    /**
     * Finds the Station model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Station the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id ) {
        if ( ( $model = Station::findOne( $id ) ) !== null ) {
            return $model;
        }

        throw new NotFoundHttpException( 'The requested page does not exist.' );
    }
}
