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
    const STATUS_DELETED = 3;
    const STATUS_ACTIVE = 1;

    public $measurement_interval_name;
    public $station_name;

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
            'name' => 'Название загрузки',
            'filename' => 'Название исходного файла',
            'id_station' => 'Станция',
            'id_measurement_interval' => 'Интервал измерений',
            'station_name' => 'Название станции',
            'measurement_interval_name' => 'Интервал измерений',
            'status' => 'Status',
            'crtime' => 'Crtime',
        ];
    }

    /**
     * Finds Uploading by id
     * @param $id
     * @return Uploading|null
     */
    public static function findById( $id ) {
        $res = static::findOne( [ 'id' => $id ] );
        $res->station_name = Station::findById( $res->id_station )->name;
        $res->measurement_interval_name = MeasurementInterval::findById( $res->id_measurement_interval )->name;
        return $res;
    }

    /**
     * Загружает данные в базу
     * @param $name
     * @param $id_station
     * @param $id_measurement_interval
     * @param $filename
     * @param $comment
     * @param $data
     * @throws \Throwable
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

        //У нас в первой строке названия колонок с данными. Во второй - единицы измерения. В третьей - тип значения ( среднее, минимальное, максимальное )
        //Первая и вторая колонка - дата и время.
        if ( $tmp_data[0][0] !== 'Date' ) {
            throw new \Exception( "Не найдена колонка с датой в файле {$this->filename}", ExceptionHelper::FIELD_NOT_FOUND );
        }
        if ( $tmp_data[0][1] !== 'Time' ) {
            throw new \Exception( "Не найдена колонка со временем в файле {$this->filename}", ExceptionHelper::FIELD_NOT_FOUND );
        }
        if ( count( $tmp_data ) < 3 ) {
            throw new \Exception( "Не найдены строки с данными в файле {$this->filename}", ExceptionHelper::FIELD_NOT_FOUND );
        }

        for ( $i = 3; $i < count( $tmp_data ); $i++ ) {
            $sqlstr_header = "insert into {$station_type['measurements_table_name']}( ";
            $sqlstr_footer = "values ( ";

            $sqlstr_header .= $station_type['data_format']['DateTime']['column_name'] . ", ";
            $sqlstr_footer .= ":{$station_type['data_format']['DateTime']['column_name']}, ";;
            $sql_parameters[$station_type['data_format']['DateTime']['column_name']] = $tmp_data[$i][0] . 'T' . $tmp_data[$i][1] . $station->timezone;
            for ( $j = 2; $j < count( $tmp_data[0] ); $j++ ) {
                //Несколько полей есть в файле данных, но не предусмотрены к загрузке
                //Мега-производительность не требуется, поэтому SQL будем генерировать каждый раз заново. Это если кто-то захочет оптимизаций
                if ( array_key_exists( $tmp_data[0][$j], $station_type['data_format'] ) ) {
                    $sqlstr_header .= $station_type[ 'data_format' ][ $tmp_data[ 0 ][ $j ] ][ 'column_name' ];
                    $sqlstr_footer .= ":{$station_type['data_format'][$tmp_data[0][$j]]['column_name']}";
                    //Не забываем менять запятые на точки
                    $sql_parameters[ $station_type[ 'data_format' ][ $tmp_data[ 0 ][ $j ] ][ 'column_name' ] ] = str_replace( ',', '.', $tmp_data[ $i ][ $j ] );
                    $sqlstr_header .= ', ';
                    $sqlstr_footer .= ', ';
                }
            }
            //Заполняем служебные поля ( id_station, id_uploading, id_measurement_interval )
            $sqlstr_header .= "id_station, id_uploading, id_measurement_interval";
            $sqlstr_footer .= ":id_station, :id_uploading, :id_measurement_interval";
            $sql_parameters['id_station'] = $this->id_station;
            $sql_parameters['id_uploading'] = $this->id;
            $sql_parameters['id_measurement_interval'] = $this->id_measurement_interval;
            //Закрываем скобки
            $sqlstr_header .= ' )';
            $sqlstr_footer .= ' )';
            //Особой производительности нам пока не нужно, поэтому с prepare одним на всю пачку запросов не заморачиваемся
            $sqlstr = $sqlstr_header . " " . $sqlstr_footer;
            DB::query( $sqlstr, $sql_parameters );
        }
    }

    function delete() {
        if ( !DB::hasBegun() ) {
            throw new \Exception( "This function must be called within a transaction", ExceptionHelper::INVALID_PARAMETER );
        }

        $sqlstr = "select measurements_table_name
from station_types
where id = ( select id_type from stations where id = :id_station )";
        $query = DB::query( $sqlstr, [ 'id_station' => $this->id_station ] );
        $measurements_table_name = $query[0]['measurements_table_name'];

        $sqlstr = "delete from {$measurements_table_name} where id_uploading = :id_uploading";
        DB::query( $sqlstr, [ 'id_uploading' => $this->id ] );

        $res = parent::delete();
        if ( $res === false ) {
            throw new \Exception( "Delete failed", ExceptionHelper::ERROR_GENERAL );
        }

        return true;
    }
}
