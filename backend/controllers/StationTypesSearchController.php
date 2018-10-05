<?php

namespace backend\controllers;

use code\helpers\Flash;
use Yii;
use common\models\StationType;
use backend\models\StationTypesSearchForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StationTypeSearchController implements the CRUD actions for StationType model.
 */
class StationTypesSearchController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'POST' ],
                ],
            ],
        ];
    }

    /**
     * Lists all StationType models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new StationTypesSearchForm();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );

        Flash::AddAll( $searchModel );

        return $this->render( 'index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ] );
    }

    /**
     * Displays a single StationType model.
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
     * Creates a new StationType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new StationTypesSearchForm();

        if ( $model->load( Yii::$app->request->post() ) ) {
            $id_station_type = $model->createStationType();
            if ( $id_station_type !== false ) {
                return $this->redirect( [ 'view', 'id' => $id_station_type ] );
            }
        }

        Flash::AddAll( $model );

        return $this->render( 'create', [
            'model' => $model,
        ] );
    }

    /**
     * Updates an existing StationType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate( $id ) {
        $model = $this->findModel( $id );

        if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
            return $this->redirect( [ 'view', 'id' => $model->id ] );
        } else {
            Flash::AddAll( $model );
            return $this->render( 'update', [
                'model' => $model,
            ] );
        }
    }

    /**
     * Deletes an existing StationType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete( $id ) {
        $model = new StationTypesSearchForm( $id );

        if ( $model->deleteStationType( $id ) ) {
            return $this->redirect( [ 'index' ] );
        } else {
            Flash::AddAll( $model );
            return $this->render( 'view', [
                'model' => $model,
            ] );
        }
    }

    /**
     * Finds the StationType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StationType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id ) {
        if ( ( $model = StationType::findOne( $id ) ) !== null ) {
            return $model;
        }

        throw new NotFoundHttpException( 'The requested page does not exist.' );
    }
}
