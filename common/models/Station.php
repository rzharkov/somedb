<?php

namespace common\models;

use code\helpers\DB;
use Yii;

/**
 * This is the model class for table "stations".
 *
 * @property int $id
 * @property string $name Условное название измерительной станции
 * @property int $id_type Тип измерительной станции из списка station_types
 * @property string $address Адрес, координаты.
 * @property string $comment Комментарии
 * @property string $timezone
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
            [ [ 'name', 'address', 'comment', 'timezone' ], 'string' ],
            [ 'timezone', 'match', 'pattern' => '#^[\+|\-][0-9]+$#' ],
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
            'name' => 'Название станции',
            'id_type' => 'Тип измерительной станции из списка station_types',
            'address' => 'Адрес, координаты.',
            'comment' => 'Комментарии',
            'crtime' => 'Время добавления в базу',
            'timezone' => 'Часовой пояс',
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

    /**
     * Returns actual list of station types
     * @return array
     * @throws \Throwable
     */
    public static function getAvailableList() {
        $query = DB::query(
            "select id, name from stations where status = :status order by name",
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
