<?php

namespace app\models;

use code\helpers\DB;
use code\helpers\Flash;
use code\helpers\Log;
use common\models\MeasurementInterval;
use common\models\Station;
use common\models\StationType;
use common\models\Uploading;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class DataUploadForm extends Model {
    public $id_station;
    public $upload_name;
    public $filename;
    public $id_measurement_interval;
    public $comment;

    /**
     * @var UploadedFile file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [ [ 'file' ], 'file' ],
            [ [ 'upload_name', 'id_station', 'id_measurement_interval', 'file' ], 'required' ],
            [ [ 'upload_name', 'comment' ], 'string' ],
            [ [ 'id_station', 'id_measurement_interval' ], 'integer' ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'id_station' => 'Название измерительной станции',
            'id_measurement_interval' => 'Интервал измерений',
            'file' => 'Файл с данными',
            'upload_name' => 'Название загрузки',
            'comment' => 'Комментарий',
            'crtime' => 'Время добавления в базу',
        ];
    }

    /**
     * Returns actual list of stations
     * @return array
     */
    public function getAvailableStationsList() {
        try {
            return Station::getAvailableList();
        } catch ( \throwable $e ) {
            Log::getUserMessage( $e );
            return [];
        }
    }

    /**
     * Returns actual measurement intervals list
     * @return array
     */
    public function getAvailableMeasurementIntervalsList() {
        try {
            return MeasurementInterval::getAvailableList();
        } catch ( \throwable $e ) {
            Log::getUserMessage( $e );
            return [];
        }
    }

    public function Upload() {
        try {
            DB::begin();
            $data = file_get_contents( $this->file->tempName );
            $uploading = new Uploading();

            $uploading->Create( $this->upload_name, $this->id_station, $this->id_measurement_interval, $this->filename, $this->comment, $data );

            //DB::commit();
            DB::rollback();
            return true;
        } catch ( \Throwable $e ) {
            DB::rollback();
            $this->addError( Flash::ATTRIBUTE_SYSTEM, Log::getUserMessage( $e ) );
            return false;
        }
    }
}
