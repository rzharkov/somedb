<?php

namespace app\models;

use code\helpers\Log;
use common\models\Station;
use common\models\StationType;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class DataUploadForm extends Model {
    public $id_station;

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
}
