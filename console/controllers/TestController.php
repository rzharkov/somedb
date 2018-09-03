<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 25.07.2018
 * Time: 10:28
 */

namespace console\controllers;

use app\models\UsersTree;
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

    public function actionUsersTreeChildCountCalc() {
        $count = 1;
        while ( $count > 0 ) {
            DB::begin();
            $sqlstr = "select id_user, path || '.%' as path from users_tree where child_count is null limit 1000 for update skip locked";
            $users = DB::query( $sqlstr );
            $count = count( $users );
            Log::log( $count );
            foreach ( $users as $user ) {
                $sqlstr = "select count(*) as cnt from users_tree where path like :path and id_user != :id_user";
                $query = DB::query( $sqlstr, [ 'path' => $user[ 'path' ], 'id_user' => $user[ 'id_user' ] ] );
                $tmp = UsersTree::findOne( [ 'id_user' => $user[ 'id_user' ] ] );
                //Log::log( $tmp->child_count );
                $tmp->child_count = $query[ 0 ][ 'cnt' ];
                //Log::log( $tmp->child_count );
                $tmp->save();
            }
            DB::commit();
        }
    }
}