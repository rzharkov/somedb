<?php

namespace common\models;

use code\helpers\DB;
use Yii;

/**
 * This is the model class for table "station_types".
 *
 * @property int $id
 * @property string $name Название типа станции
 * @property integer $status
 * @property string $crtime
 */
class StationType extends \yii\db\ActiveRecord {
    const STATUS_DELETED = 3;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'station_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'name', 'measurements_table_name' ], 'required' ],
            [ [ 'name', 'measurements_table_name', 'data_format' ], 'string' ],
            [ [ 'crtime', 'data_format', 'measurements_table_name' ], 'safe' ],
            [ 'status', 'default', 'value' => self::STATUS_ACTIVE ],
            [ 'status', 'in', 'range' => [ self::STATUS_ACTIVE, self::STATUS_DELETED ] ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Название типа станции',
            'status' => 'Статус',
            'data_format' => 'Формат и описание данных',
            'measurements_table_name' => 'Таблица с данными измерений',
            'crtime' => 'Время добавления в базу',
        ];
    }

    /**
     * Finds the station by id
     * @param $id
     * @return null|\yii\db\ActiveRecord
     */
    public static function findById( $id ) {
        $res = static::findOne( [ 'id' => $id ] );
        return $res;
    }

    /**
     * Finds a station
     * @param $condition
     * @return null|\yii\db\ActiveRecord
     */
    public static function findOne( $condition ) {
        $res = parent::findOne( $condition );
        return $res;
    }

    /**
     * Returns actual list of station types
     * @return array
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public static function getAvailableList() {
        $query = DB::query(
            "select id, name from station_types where status = :status order by name",
            [
                'status' => self::STATUS_ACTIVE
            ]
        );
        foreach ( $query as $row ) {
            $res[ $row[ 'id' ] ] = $row[ 'name' ];
        }
        return $res;
    }
}
