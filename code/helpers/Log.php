<?php

namespace code\helpers;

use code\log\SwitchingFileTarget;
use Yii;

class Log {

    const DEFAULT_CATEGORY = 'shmdb';

    /**
     * @param $message
     * @param string $category
     */
    public static function log( $message, $category = self::DEFAULT_CATEGORY ) {

        if(is_array($message)){
            $backtrace = debug_backtrace();
            if(isset($backtrace[1])){
                $caller = $backtrace[1];
                if(isset($caller['class'])){
                    $message['debug_info'] = $caller['class'];
                }elseif($caller['file']){
                    $message['debug_info'] = $caller['file'];
                }
                if(isset($caller['line'])){
                    $message['debug_info'] .= ": " . $caller['line'];
                }
            }
            $message = json_encode($message, JSON_PRETTY_PRINT);
        }
        \Yii::info( $message, $category );

    }

    /**
     * @param $message
     * @param string $category
     */
    public static function err( $message, $category = self::DEFAULT_CATEGORY ) {

        \Yii::error( $message, $category );

    }

    /**
     * @return array
     */
    private static function getSwitchingFileLogTargets() {

        $targets = Yii::getLogger()->dispatcher->targets;

        $switchingTargets = [];

        foreach ( $targets as $i => $v ) {

            if ( $v instanceof SwitchingFileTarget ) {
                $switchingTargets[ $i ] = $v;
            }

        }

        return $switchingTargets;

    }

    /**
     * @param $fname
     * @param string $target
     * @throws \Exception
     */
    public static function setFileLog( $fname, $target = 'common' ) {

        $targets = self::getSwitchingFileLogTargets();

        if ( array_key_exists( $target, $targets ) ) {

            $targets[ $target ]->setFile( $fname );

        } else {

            throw new \Exception( 'Target (\code\log\SwitchingFileTarget) ' . $target . ' not found' );

        }

    }

    /**
     * @param string $target
     * @throws \Exception
     */
    public static function resetFileLog( $target = 'common' ) {

        $targets = self::getSwitchingFileLogTargets();

        if ( array_key_exists( $target, $targets ) ) {

            $targets[ $target ]->resetFile();

        } else {

            throw new \Exception( 'Target (\code\components\log\SwitchingFileTarget) ' . $target . ' not found' );

        }

    }

    /**
     * @param \Exception $e
     * @return string
     */
    public static function getUserMessage( \Exception $e ) {
        $debug_backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 1 );
        $called_from = basename( $debug_backtrace[ 0 ][ 'file' ] ) . ":" . $debug_backtrace[ 0 ][ 'line' ];

        $full = $e->getMessage() .
            "\nCode:\t" . $e->getCode() .
            "\nFile:\t" . $e->getFile() . ":" . $e->getLine() .
            "\nTrace:\t" . $e->getTraceAsString() .
            "\n( getUserMessage called from: " . $called_from . " )";
        self::err( $full );
        if ( defined( 'YII_ENV' ) && YII_ENV == 'dev' ) {
            $text = $full;
        } else {
            if ( $e->getCode() > 0 ) {
                $res = $e->getMessage();
            } else {
                $res = "Error";
            }
            $text = \Yii::t('app', $res );
        }
        return $text;
    }

}