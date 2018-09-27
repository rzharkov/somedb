<?php

namespace common\models;

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
            [ [ 'name' ], 'required' ],
            [ [ 'name' ], 'string' ],
            [ [ 'crtime' ], 'safe' ],
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
            'crtime' => 'Время добавления в базу',
        ];
    }

    /**
     * Finds Station Type by id
     * @param $id
     * @return StationType|null
     */
    public static function findById( $id ) {
        return static::findOne( [ 'id' => $id ] );
    }
}
