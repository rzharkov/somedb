<?php

namespace code\helpers;

use yii\base\Model;

class Flash
{
    const ATTRIBUTE_SYSTEM = '__SYSTEM';
    public static function AddAll( Model $model ) {
        foreach ( $model->getErrors( self::ATTRIBUTE_SYSTEM ) as $error ) {
                \Yii::$app->session->addFlash( 'error', $error );
        }
    }
}