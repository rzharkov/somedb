<?php

namespace common\models;

use code\helpers\DB;
use Yii;

/**
 * This is the model class for table "measurement_intervals".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 */
class MeasurementInterval extends \yii\db\ActiveRecord {
    const STATUS_DELETED = 3;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'measurement_intervals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'name' ], 'required' ],
            [ [ 'name' ], 'string' ],
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
            'name' => 'Name',
            'status' => 'Status',
        ];
    }

    /**
     * Finds Measurement Interval by id
     * @param $id
     * @return MeasurementInterval|null
     */
    public static function findById( $id ) {
        return static::findOne( [ 'id' => $id ] );
    }

    /**
     * Returns the list of available measurement intervals
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public static function getAvailableList() {
        $query = DB::query(
            "select id, name from measurement_intervals where status = :status order by name",
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
