<?php

namespace common\models;

use code\helpers\DB;
use code\helpers\ExceptionHelper;
use Yii;

/**
 * This is the model class for table "uploadings".
 *
 * @property int $id
 * @property string $name Пока не понятно что, но заполним названием файла
 * @property int id_station
 * @property int id_measurement_interval
 * @property string $filename Название файла из которого заливались данные
 * @property int $status
 * @property string $comment
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
            [ [ 'name', 'filename', 'id_station', 'id_measurement_interval' ], 'required' ],
            [ [ 'name', 'filename', 'comment' ], 'string' ],
            [ [ 'status' ], 'default', 'value' => 1 ],
            [ [ 'status', 'id_station', 'id_measurement_interval' ], 'integer' ],
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
            'id_station' => 'Идентификатор ищмерительной станции',
            'id_measurement_interval' => 'Идентификатор интервала измерений',
            'status' => 'Status',
            'crtime' => 'Crtime',
        ];
    }

    /**
     * Загружает данные в базу
     * @param $name
     * @param $id_station
     * @param $id_measurement_interval
     * @param $filename
     * @param $comment
     * @param $data
     * @throws \Exception
     */
    public function Create( $name, $id_station, $id_measurement_interval, $filename, $comment, $data ) {
        if ( !DB::hasBegun() )
            throw new \Exception( 'Transaction must be started to Create the Uploading', ExceptionHelper::ERROR_GENERAL );
        $this->name = $name;
        $this->id_station = $id_station;
        $this->id_measurement_interval = $id_measurement_interval;
        $this->filename = $filename;
        $this->comment = $comment;

        $this->save();

        $this->refresh();
        var_dump( $this );

        var_dump( explode ( ';', $data, 3 ) );

        die();
    }
}
