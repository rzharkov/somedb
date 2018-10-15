<?php

namespace backend\controllers;

use app\models\DataUploadForm;
use app\models\UploadForm;
use code\helpers\Flash;
use code\helpers\FlashHelper;
use common\widgets\Alert;
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
        $model = new DataUploadForm();
        if ( Yii::$app->request->isPost ) {
            $model->file = UploadedFile::getInstance( $model, 'file' );

            if ( $model->file && $model->validate() ) {
                //var_dump( $model->file );
                //$model->file->saveAs( 'uploads/' . $model->file->baseName . '.' . $model->file->extension );
                $file = file_get_contents( $model->file->tempName );
                var_dump( explode( "\n", $file ) );
                die();
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
