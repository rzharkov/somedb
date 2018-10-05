<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stations".
 *
 * @property int $id
 * @property string $name Условное название измерительной станции
 * @property int $id_type Тип измерительной станции из списка station_types
 * @property string $address Адрес, координаты.
 * @property string $comment Комментарии
 * @property string $crtime
 * @property int $status
 */
class Station extends \yii\db\ActiveRecord {
    const STATUS_DELETED = 3;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'stations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'name', 'id_type', 'address' ], 'required' ],
            [ [ 'name', 'address', 'comment' ], 'string' ],
            [ [ 'id_type', 'status' ], 'integer' ],
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
            'name' => 'Условное название измерительной станции',
            'id_type' => 'Тип измерительной станции из списка station_types',
            'address' => 'Адрес, координаты.',
            'comment' => 'Комментарии',
            'crtime' => 'Время добавления в базу',
            'status' => 'Status',
        ];
    }

    /**
     * Finds Station by id
     * @param $id
     * @return Station|null
     */
    public static function findById( $id ) {
        return static::findOne( [ 'id' => $id ] );
    }
}
