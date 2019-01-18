<?php

namespace backend\models;

use code\helpers\DB;
use code\helpers\ExceptionHelper;
use code\helpers\Flash;
use code\helpers\Log;
use common\models\MeasurementInterval;
use common\models\Station;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Uploading;

class UploadingSearchForm extends Model {
    const STATUS_DELETED = 3;
    const STATUS_ACTIVE = 1;

    public $id;
    public $name;
    public $filename;
    public $status;
    public $id_measurement_interval;
    public $id_station;
    public $measurement_interval_name;
    public $station_name;
    public $crtime;
    public $comment;

    private $_uploading;

    /**
     * {@inheritdoc}
     *
     * UserSearchForm constructor.
     * @param null $id
     */
    function __construct( $id = null ) {
        parent::__construct();
        if ( $id !== null ) {
            $this->getUploading( $id );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'id', 'status', 'id_station', 'id_measurement_interval' ], 'integer' ],
            [ [ 'name', 'filename', 'comment', 'crtime' ], 'safe' ],
            [ 'status', 'in', 'range' => [ self::STATUS_ACTIVE, self::STATUS_DELETED ] ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search( $params ) {
        $query = Uploading::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider( [
            'query' => $query,
        ] );

        $this->load( $params );

        if ( !$this->validate() ) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere( [
            'id' => $this->id,
            'crtime' => $this->crtime,
        ] );

        $query->andFilterWhere( [ 'ilike', 'name', $this->name ] )
            ->andFilterWhere( [ 'ilike', 'filename', $this->filename ] )
            ->andFilterWhere( [ '=', 'id_station', $this->id_station ] )
            ->andFilterWhere( [ '=', 'id_measurement_interval', $this->id_measurement_interval ] )
            ->andFilterWhere( [ 'ilike', 'comment', $this->comment ] );

        return $dataProvider;
    }

    public function getUploading( $id ) {
        $this->_uploading = Uploading::findOne( $id );

        $this->id = $this->_uploading->id;
        $this->name = $this->_uploading->name;
        $this->comment = $this->_uploading->comment;
        $this->crtime = $this->_uploading->crtime;
        $this->status = $this->_uploading->status;
        $this->id_measurement_interval = $this->_uploading->id_measurement_interval;
        $this->id_station = $this->_uploading->id_station;
        $this->measurement_interval_name = $this->_uploading->id_station;
        $this->station_name = $this->_uploading->id_station;


        return $this->_uploading;
    }

    public function deleteUploading( $id ) {
        try {
            if ( $this->validate() ) {
                $uploading = Uploading::findById( $id );

                if ( !$uploading ) {
                    throw new \Exception( "uploading not found", ExceptionHelper::UPLOADING_NOT_FOUND );
                }

                DB::begin();
                $res = $uploading->delete();

                if( !$res ) {
                    $this->addErrors( $uploading->getErrors() );
                    throw new \Exception( "Uploading save error", ExceptionHelper::ERROR_GENERAL );
                }

                DB::commit();
                //DB::rollback();

                return $res;
            } else {
                return false;
            }
        } catch ( \Throwable $e ) {
            DB::rollback();
            $this->addError( Flash::ATTRIBUTE_SYSTEM, Log::getUserMessage( $e ) );
            return false;
        }
    }

    public function updateUploading( $id ) {
        try {
            if ( $this->validate() ) {
                $uploading = Uploading::findById( $id );

                if ( !$uploading ) {
                    throw new \Exception( "Uploading not found", ExceptionHelper::UPLOADING_NOT_FOUND );
                }

                $uploading->name = $this->name;
                $uploading->comment = $this->comment;
                $uploading->id_measurement_interval = $this->id_measurement_interval;
                $uploading->id_station = $this->id_station;

                //TODO:: данные загрузки надо тоже исправить!

                DB::begin();
                $res = $uploading->save();

                if ( !$res ) {
                    $this->addErrors( $uploading->getErrors() );
                    throw new \Exception( "Uploading save error", ExceptionHelper::ERROR_GENERAL );
                }

                //DB::rollback();
                DB::commit();

                return $res;
            } else {
                return false;
            }
        } catch ( \Throwable $e ) {
            DB::rollback();
            $this->addError( Flash::ATTRIBUTE_SYSTEM, Log::getUserMessage( $e ) );
            return false;
        }
    }

}
