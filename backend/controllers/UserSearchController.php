<?php

namespace backend\controllers;

use code\helpers\Flash;
use Yii;
use common\models\User;
use backend\models\UserSearchForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserSearchController implements the CRUD actions for User model.
 */
class UserSearchController extends Controller {
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new UserSearchForm();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );

        Flash::AddAll( $searchModel );

        return $this->render( 'index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ] );
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView( $id ) {
        $model = $this->findUser( $id );

        Flash::AddAll( $model );

        return $this->render( 'view', [
            'model' => $model,
        ] );
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new User();

        if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
            return $this->redirect( [ 'view', 'id' => $model->id ] );
        }

        Flash::AddAll( $model );

        return $this->render( 'create', [
            'model' => $model,
        ] );
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate( $id ) {
        $model = new UserSearchForm( $id );

        if ( $model->load( Yii::$app->request->post() ) && $model->updateUser( $id ) ) {
            return $this->redirect( [ 'view', 'id' => $model->id ] );
        } else {
            Flash::AddAll( $model );
            return $this->render( 'update', [
                'model' => $model,
            ] );
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete( $id ) {
        $model = new UserSearchForm( $id );

        if ( $model->deleteUser( $id ) ) {
            return $this->redirect( [ 'index' ] );
        } else {
            Flash::AddAll( $model );
            return $this->render( 'view', [
                'model' => $model,
            ] );
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserSearchForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findUser( $id ) {
        $model = new UserSearchForm( $id );

        if ( $model === null ) {
            throw new NotFoundHttpException( 'The requested page does not exist.' );
        }

        return $model;
    }
}
