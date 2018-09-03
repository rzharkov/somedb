<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 13.07.2018
 * Time: 15:12
 */

namespace code\helpers;


class LockFile {
    private static $_locks = array();

    /**
     * Returns the directory name for PID files
     * Creates it when necessary
     * If CRON_PID_DIR is defined, CRON_PID_DIR will returned
     * Default is /tmp/run/project1_cron/
     * @return string the directory name
     * @throws \Exception ExceptionHelper::FILE_NOT_FOUND
     */
    private static function _getPidDirectory() {
        $pid_dir = "/tmp/run/project1_cron";
        if ( defined( 'CRON_PID_DIR' ) && CRON_PID_DIR ) {
            $pid_dir = CRON_PID_DIR;
        }

        //Log::log( "PID dir is '" . $pid_dir . "'" );

        if ( !file_exists( $pid_dir ) ) {
            Log::log( "$pid_dir not found" );
            $res = mkdir( $pid_dir, 0775, true );
            if ( !$res )
                throw new \Exception( "PID directory not exists and cannot be created", ExceptionHelper::FILE_NOT_FOUND );
            Log::log( "$pid_dir created" );
        }

        if ( substr( $pid_dir, -1 ) != '/' )
            $pid_dir = $pid_dir . '/';

        return $pid_dir;
    }

    /**
     * Returns the string that contains script arguments
     * @return string
     */
    public static function GetRunArgs() {
        $args = '';
        if ( isset( $_SERVER[ 'argv' ] )
            && count( $_SERVER[ 'argv' ] ) > 1
        ) {
            $args = $_SERVER[ 'argv' ];
            unset( $args[ 0 ] );
            $args = '_' . implode( '_', $args );
        }

        return $args;
    }

    /**
     * Returns full PID file name based on received key<br>
     * By default /tmp/run/project1_cron/[PHP_SCRIPT_NAME]_[ARG].pid<br>
     * Directory may changed by using CRON_PID_DIR constant<br>
     *
     * @param string $key - key name
     *
     * @return string - full PID file name. By default /tmp/run/project1_cron/[PHP_SCRIPT_NAME]_[ARG].pid
     * @throws \Exception
     */
    public static function GetPIDFileName( $key = '' ) {
        if ( $key == '' ) {
            $tmp = debug_backtrace();
            $tmp = array_pop( $tmp );
            $tmp = $tmp[ 'file' ];
            $key = basename( $tmp );
        }

        $key .= self::GetRunArgs();

        $key = strtolower( $key );
        $key = preg_replace( '/[^a-z0-9]/', '_', $key );

        $pid_directory = self::_getPidDirectory();

        $pid_file_name = $pid_directory . $key . ".pid";

        return $pid_file_name;
    }

    /**
     * Creates and locks the PID file<br>
     *
     * @param string $key
     *
     * @return mixed - returns PID file handle
     * @throws \Exception
     */
    public static function GetLock( $key = '' ) {
        Log::log( "Get lock with key '" . $key . "' started" );
        if ( isset( self::$_locks[ $key ] ) ) {
            return self::$_locks[ $key ];
        }

        $pid_file_name = self::GetPIDFileName( $key );

        Log::log( "PID file is " . $pid_file_name );

        $pid_file = fopen( $pid_file_name, 'c' );

        if ( !$pid_file ) {
            throw new \Exception( "PID file not exists and cannot be created", ExceptionHelper::FILE_NOT_FOUND );
        }

        $pid_file_lock = flock( $pid_file, LOCK_EX | LOCK_NB );

        if ( !$pid_file_lock ) {
            fclose( $pid_file );
            $pid_file = fopen( $pid_file_name, 'r' );
            if ( filesize( $pid_file_name ) ) {
                $pid = fread( $pid_file, min( filesize( $pid_file_name ), 100 ) );
            } else {
                $pid = 'unknown';
            }
            fclose( $pid_file );
            throw new \Exception( "PID file exists and blocked by '{$pid}'", ExceptionHelper::ERROR_GENERAL );
        }

        $res = ftruncate( $pid_file, 0 );

        if ( !$res ) {
            throw new \Exception( "PID file cannot be truncated", ExceptionHelper::ERROR_GENERAL );
        }

        $res = fwrite( $pid_file, getmypid() );
        if ( $res === false ) {
            throw new \Exception( "Cannot write to PID file", ExceptionHelper::ERROR_GENERAL );
        }

        self::$_locks[ $key ] = $pid_file;

        if ( defined( "TICKS_DECLARED" ) && TICKS_DECLARED == 1 ) {
//            Log::log( "Calling pcntl_signal ( pid=" . getmypid() . " )" );
            $res = pcntl_signal( SIGTERM, array( "code\helpers\LockFile", "sig_handler" ) );
            if ( !$res ) {
                Log::err( "pcntl_signal failed!" );
            }
            $res = pcntl_signal( SIGINT, array( "code\helpers\LockFile", "sig_handler" ) );
            if ( !$res ) {
                Log::err( "pcntl_signal failed!" );
            }
        }

        return self::$_locks[ $key ];
    }

    /**
     * Releases the lock
     *
     * @param string $key
     *
     * @return bool - TRUE when succeed. <b>Lock releases in any case</b>
     * @throws \Exception
     */
    public static function ReleaseLock( $key = '' ) {
        Log::log( "Release lock with key '" . $key . "'" );
        if ( isset( self::$_locks[ $key ] ) ) {
            flock( self::$_locks[ $key ], LOCK_UN );
            fclose( self::$_locks[ $key ] );
            unset( self::$_locks[ $key ] );
        }

        $pid_file_name = self::GetPIDFileName( $key );

        $res = unlink( $pid_file_name );
        if ( !$res ) {
            Log::err( "Cannot delete PID file" );
        }
        return $res;
    }

    /**
     * The magic function that releases file lock when SIGTERM received
     * Registers when TICKS_DECLARED = 1 is defined and works only when declare( ticks = 1 ) function called
     * @param $signo
     * @throws \Exception
     */
    public static function sig_handler( $signo ) {
        Log::log( "received {$signo}" );
        switch ( $signo ) {
            case SIGTERM:
            case SIGINT:
                self::ReleaseLock();
                exit;
                break;
            default:
        }
    }
}
//EOF
