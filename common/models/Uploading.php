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
            throw new \Exception( 'Transaction must be started to create the Uploading', ExceptionHelper::ERROR_GENERAL );
        $this->name = $name;
        $this->id_station = $id_station;
        $this->id_measurement_interval = $id_measurement_interval;
        $this->filename = $filename;
        $this->comment = $comment;

        $this->save();
        $this->refresh();

        //Добудем сопоставление колонок файла и полей в базе
        $station = Station::findById( $this->id_station );
        if ( $station === null  ) {
            throw new \Exception( "Станция с идентификатором '{$this->id_station}' не найдена", ExceptionHelper::STATION_NOT_FOUND );
        }
        $station_type = StationType::findById( $station->id_type );
        if ( $station_type === null  ) {
            throw new \Exception( "Кривой идентификатор типа станции '{$station->id_type}'", ExceptionHelper::STATION_NOT_FOUND );
        }

        //var_dump( $station_type->data_format['Tens30.1'] );

        $tmp_data = [];
        foreach ( $data as $data_row ) {
            $tmp_data[] = explode( "\t", $data_row );
        }

        //Проверям входные данные
        //Первая и вторая колонка - дата и время.
        var_dump( $tmp_data );

        //var_dump( explode ( ';', $data, 3 ) );

        die();
    }
}
