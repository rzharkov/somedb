<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "measurement_intervals".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 */
class MeasurementInterval extends \yii\db\ActiveRecord {
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
            [ [ 'status' ], 'default', 'value' => 1 ],
            [ [ 'status' ], 'integer' ],
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
}
