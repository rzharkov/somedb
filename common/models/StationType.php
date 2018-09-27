<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "station_types".
 *
 * @property int $id
 * @property string $name Название типа станции
 * @property string $crtime
 */
class StationType extends \yii\db\ActiveRecord {
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
}
