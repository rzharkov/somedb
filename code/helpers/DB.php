<?php


namespace code\helpers;


use yii\db\Transaction;

class DB {
    /**
     * @var Transaction
     */
    private static $transaction;

    /**
     * Begin transaction
     * @throws \Throwable
     * @return Transaction
     */
    public static function begin() {
        Log::log( "Begin" );
        if ( self::hasBegun() ) {
            throw new \Exception( 'Transaction already started', ExceptionHelper::TRANSACTION_FAILED );
        }
        self::$transaction = \Yii::$app->db->beginTransaction();

        return self::$transaction;
    }

    /**
     * Commit transaction
     * @throws \Throwable
     */
    public static function commit() {
        Log::log( "Commit" );
        if ( !self::hasBegun() ) {
            throw new \Exception( 'Transaction is not started' );
        }
        self::$transaction->commit();
        self::reset();
    }

    /**
     * Rollback transaction
     */
    public static function rollback() {
        Log::log( "Rollback" );
        if ( self::hasBegun() ) {
            self::$transaction->rollBack();
        }
        self::reset();
    }

    /**
     * @param string $sql
     * @param array $params
     * @param null $fetchMode
     * @return array
     * @throws \Throwable
     */
    public static function query( $sql, $params = [], $fetchMode = null ) {
        try {
            return \Yii::$app->db->createCommand( $sql, $params )->queryAll( $fetchMode );
        } catch ( \Throwable $e ) {
            Log::getUserMessage( $e );
            throw $e;
        }
    }

    /**
     * @param $table_name
     * @param $id
     * @param $data
     * @return int
     * @throws \yii\db\Exception
     */
    public static function update( $table_name, $id, $data ) {
        return \Yii::$app->db->createCommand()->update( $table_name, $data, 'id = :id', [ 'id' => $id ] )->execute();
    }

    /**
     * @param $table_name
     * @param $data
     * @return int
     * @throws \yii\db\Exception
     */
    public static function upsert( $table_name, $data ) {
        return \Yii::$app->db->createCommand()->upsert( $table_name, $data, $data, [] )->execute();
    }

    /**
     * @return bool
     */
    public static function hasBegun() {
        return !empty( self::$transaction );
    }

    private static function reset() {
        self::$transaction = null;
    }
}