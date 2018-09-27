<?php

namespace backend\models;

use code\helpers\DB;
use code\helpers\ExceptionHelper;
use code\helpers\Flash;
use code\helpers\Log;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StationType;

/**
 * StationTypeSearchForm represents the model behind the search form of `common\models\StationType`.
 */
class StationTypesSearchForm extends Model {
    public $id;
    public $name;
    public $status;
    public $crtime;

    private $_station;

    /**
     * {@inheritdoc}
     *
     * UserSearchForm constructor.
     * @param null $id
     */
    function __construct( $id = null ) {
        parent::__construct();
        if ( $id !== null ) {
            $this->getStationType( $id );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'id' ], 'integer' ],
            [ [ 'name', 'crtime' ], 'safe' ],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search( $params ) {
        $query = StationType::find();

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
            'name' => $this->name,
        ] );

        $query->andFilterWhere( [ 'ilike', 'name', $this->name ] );

        return $dataProvider;
    }

    public function getStationType( $id ) {
        $this->_station = StationType::findOne( $id );

        $this->id = $this->_station->id;
        $this->name = $this->_station->name;
        $this->status = $this->_station->status;

        return $this->_station;
    }

    public function deleteStationType( $id ) {
        try {
            if ( $this->validate() ) {
                $station_type = StationType::findById( $id );

                if ( !$station_type ) {
                    throw new \Exception( "station type not found", ExceptionHelper::USER_NOT_FOUND );
                }

                $station_type->status = $station_type::STATUS_DELETED;

                DB::begin();
                $res = $station_type->save();

                if( !$res ) {
                    $this->addErrors( $station_type->getErrors() );
                    throw new \Exception( "Station Type save error", ExceptionHelper::ERROR_GENERAL );
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

    public function createStationType() {
        try {
            if ( $this->validate() ) {
                $station_type = new StationType();

                if ( !$station_type ) {
                    throw new \Exception( "station type not found", ExceptionHelper::USER_NOT_FOUND );
                }

                $station_type->name = $this->name;

                DB::begin();
                $res = $station_type->save();

                if( !$res ) {
                    $this->addErrors( $station_type->getErrors() );
                    throw new \Exception( "User save error", ExceptionHelper::ERROR_GENERAL );
                }

                $station_type->refresh();

                //DB::rollback();
                DB::commit();

                return $station_type->id;
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
