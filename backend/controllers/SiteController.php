<?php

namespace backend\controllers;

use common\widgets\Alert;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\ProfileForm;

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
        return $this->render( 'index' );
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
            foreach ( $model->getErrors() as $attribute => $errors ) {
                foreach ( $errors as $error ) {
                    Yii::$app->session->addFlash( 'error', $error );
                }
            }

            return $this->render( 'profile', [ 'model' => $model ] );
        }
    }

}
