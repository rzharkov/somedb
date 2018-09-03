<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 25.07.2018
 * Time: 10:28
 */

namespace console\controllers;

use common\models\User;
use yii;
use code\console\Controller;
use code\helpers\DB;
use code\helpers\Log;

class TestController extends Controller {
    public function actionIndex() {
        Log::log( "Hello, world!" );
        $sqlstr = "select user || ' ' || now() as date";
        $query = DB::query( $sqlstr );
        Log::log( $query[ 0 ][ 'date' ] );
    }

    public function actionInitAdmin() {
        $res = \yii\helpers\Console::input( "Do you realy want to clear and initialize permitions? (y/N)" );

        if ( $res === 'y' ) {
            Log::log( "Initializing roles" );

            try {
                DB::begin();
                $auth = Yii::$app->authManager;

                $auth->removeAll(); //На всякий случай удаляем старые данные из БД...

                $admin = $auth->createRole( 'admin' );
                $viewer = $auth->createRole( 'viewer' );

                $auth->add( $admin );
                $auth->add( $viewer );

                $viewAdminPage = $auth->createPermission( 'viewAdminPage' );
                $viewAdminPage->description = 'Просмотр админки';

                $auth->add( $viewAdminPage );

                $auth->addChild( $admin, $viewAdminPage );

                $user = new User();
                $user->username = 'admin';
                $user->password = '123456';
                $user->email = 'admin@local';
                $user->generateAuthKey();
                $user->save();

                $user->refresh();

                $auth->assign( $admin, $user->id );

                //DB::rollback();
                DB::commit();

            } catch ( \Throwable $e ) {
                DB::rollback();
                Log::getUserMessage( $e );
            }
            Log::log( 'Initializing complete' );
        } else {
            Log::log( 'Exit' );
        }
    }
}