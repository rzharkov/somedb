<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "uploadings".
 *
 * @property int $id
 * @property string $name Пока не понятно что, но заполним названием файла
 * @property string $filename Название файла из которого заливались данные
 * @property int $status
 * @property string $crtime
 */
class Uploading extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'uploadings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'name', 'filename' ], 'required' ],
            [ [ 'name', 'filename' ], 'string' ],
            [ [ 'status' ], 'default', 'value' => 1 ],
            [ [ 'status' ], 'integer' ],
            [ [ 'crtime' ], 'safe' ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Пока не понятно что, но заполним названием файла',
            'filename' => 'Название файла из которого заливались данные',
            'status' => 'Status',
            'crtime' => 'Crtime',
        ];
    }
}
