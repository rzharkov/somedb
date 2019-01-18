<?php

namespace backend\controllers;

use backend\models\UploadingSearchForm;
use code\helpers\Flash;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\ProfileForm;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [ 'login', 'error' ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [ 'logout', 'index', 'profile' ],
                        'allow' => true,
                        'roles' => [ '@' ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => [ 'post' ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $model = new UploadingSearchForm();
        $model->scenario = 'Create';
        if ( Yii::$app->request->isPost ) {
            if ( $model->load( Yii::$app->request->post() ) ) {
                $model->file = UploadedFile::getInstance( $model, 'file' );
                $model->filename = $model->file->name;
                if ( $model->file && $model->validate() ) {
                    $res = $model->Upload();
                    if ( !$res ) {
                        Flash::AddAll( $model );
                    } else {
                        \Yii::$app->session->addFlash( 'success', 'Файл "' . $model->filename . '" успешно загружен' );
                    }
                }
            }
        }

        return $this->render( 'index', [ 'model' => $model ] );
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        if ( !Yii::$app->user->isGuest ) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ( $model->load( Yii::$app->request->post() ) && $model->login() ) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render( 'login', [
                'model' => $model,
            ] );
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionProfile() {
        $model = new ProfileForm();

        if ( $model->load( Yii::$app->request->post() ) && $model->saveNewPassword() ) {
            return $this->goHome();
        } else {
            Flash::AddAll( $model );
            return $this->render( 'profile', [ 'model' => $model ] );
        }
    }

}
