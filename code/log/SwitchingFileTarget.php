<?php

namespace code\log;


use yii\base\Component;
use yii\helpers\Console;
use yii\helpers\VarDumper;
use yii\log\FileTarget;

use Yii;
use yii\log\Logger;

class SwitchingFileTarget extends FileTarget {

    public $logDir;

    public $writeToConsole = false;

    private $filesChain = [];

    public function init() {

        if ( $this->logDir === null ) {
            $this->logDir = Yii::$app->getRuntimePath() . '/logs/';
        }

        if ( $this->logFile === null ) {
            $this->logFile = $this->logDir . 'app_' . date( 'Y-m-d' ) . '.log';
        } else {
            $this->logFile = $this->logDir . $this->logFile;
        }

        parent::init();

    }

    public function export() {

        parent::export();

        if ( $this->writeToConsole ) {

            /*foreach ($this->messages as $m) {
                $this->printConsoleMessage( $m );
            }*/
            $text = implode( "\n", array_map( [ $this, 'formatConsoleMessage' ], $this->messages ) ) . "\n";
            echo $text;
        }
    }

    public function formatConsoleMessage( $message ) {

        list( $text, $level, $category, $timestamp ) = $message;
        $level = Logger::getLevelName( $level );
        if ( !is_string( $text ) ) {
            // exceptions may not be serializable if in the call stack somewhere is a Closure
            if ( $text instanceof \Throwable || $text instanceof \Exception ) {
                $text = (string)$text;
            } else {
                $text = VarDumper::export( $text );
            }
        }
        $traces = [];
        if ( isset( $message[ 4 ] ) ) {
            foreach ( $message[ 4 ] as $trace ) {
                $traces[] = "in {$trace['file']}:{$trace['line']}";
            }
        }

        $prefix = $this->getMessagePrefix( $message );

        if ( $level == 'error' ) {
            $level_format = Console::ansiFormat( '[' . $level . ']', [ Console::FG_RED ] );
        } elseif ( $level == 'info' ) {
            $level_format = Console::ansiFormat( '[' . $level . ']', [ Console::FG_GREEN ] );
        } else {
            $level_format = $level;
        }

        return Console::ansiFormat( $this->getTime( $timestamp ), [ Console::BOLD ] ) . ' ' . $prefix . $level_format . ' ' . $text
            . ( empty( $traces ) ? '' : "\n    " . implode( "\n    ", $traces ) );
    }

    public function setFile( $fname ) {

        Yii::getLogger()->flush( true );

        $this->filesChain[] = $this->logFile;

        $this->logFile = $this->logDir . $fname . '_' . date( 'Y-m-d' ) . '.log';

    }

    public function resetFile() {

        Yii::getLogger()->flush( true );

        if ( count( $this->filesChain ) ) {
            $this->logFile = array_pop( $this->filesChain );
        }

    }

}