<?php

namespace backend\models;

use code\helpers\DB;
use code\helpers\ExceptionHelper;
use code\helpers\Flash;
use code\helpers\Log;
use common\models\StationType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Station;

class StationSearchForm extends Model {
	const STATUS_DELETED = 3;
	const STATUS_ACTIVE = 1;

	public $id;
	public $name;
	public $status;
	public $crtime;
	public $id_type;
	public $address;
	public $timezone;
	public $comment;

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
			$this->getStation( $id );
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[ [ 'id', 'id_type', 'status' ], 'integer' ],
			[ [ 'name', 'address', 'comment', 'crtime', 'timezone' ], 'safe' ],
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
	 * Returns actual list of station types
	 * @return array
	 */
	public function getAvailableStationTypesList() {
		try {
			return StationType::getAvailableList();
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
		$query = Station::find();

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
			'id_type' => $this->id_type,
			'crtime' => $this->crtime,
		] );

		$query->andFilterWhere( [ 'ilike', 'name', $this->name ] )
			->andFilterWhere( [ 'ilike', 'address', $this->address ] )
			->andFilterWhere( [ 'ilike', 'comment', $this->comment ] );

		return $dataProvider;
	}

	public function getStation( $id ) {
		$this->_station = Station::findOne( $id );

		$this->id = $this->_station->id;
		$this->name = $this->_station->name;
		$this->status = $this->_station->status;
		$this->crtime = $this->_station->crtime;
		$this->address = $this->_station->address;
		$this->timezone = $this->_station->timezone;
		$this->comment = $this->_station->comment;

		return $this->_station;
	}

	public function createStation() {
		try {
			if ( $this->validate() ) {
				$station = new Station();

				if ( !$station ) {
					throw new \Exception( "station not found", ExceptionHelper::USER_NOT_FOUND );
				}

				$station->name = $this->name;
				$station->address = $this->address;
				$station->comment = $this->comment;
				$station->timezone = $this->timezone;
				$station->id_type = $this->id_type;

				DB::begin();
				$res = $station->save();

				if ( !$res ) {
					$this->addErrors( $station->getErrors() );
					throw new \Exception( "Station save error", ExceptionHelper::ERROR_GENERAL );
				}

				$station->refresh();

				//DB::rollback();
				DB::commit();

				return $station->id;
			} else {
				return false;
			}
		} catch ( \Throwable $e ) {
			DB::rollback();
			$this->addError( Flash::ATTRIBUTE_SYSTEM, Log::getUserMessage( $e ) );
			return false;
		}
	}

	public function updateStation( $id ) {
		try {
			if ( $this->validate() ) {
				$station = Station::findById( $id );

				if ( !$station ) {
					throw new \Exception( "Station not found", ExceptionHelper::USER_NOT_FOUND );
				}

				$station->name = $this->name;
				$station->address = $this->address;
				$station->comment = $this->comment;
				$station->timezone = $this->timezone;
				$station->id_type = $this->id_type;
				$station->status = $this->status;

				DB::begin();
				$res = $station->save();

				if ( !$res ) {
					$this->addErrors( $station->getErrors() );
					throw new \Exception( "Station save error", ExceptionHelper::ERROR_GENERAL );
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

	public function deleteStation( $id ) {
		try {
			if ( $this->validate() ) {
				$station = Station::findById( $id );

				if ( !$station ) {
					throw new \Exception( "station not found", ExceptionHelper::STATION_NOT_FOUND );
				}

				$station->status = Station::STATUS_DELETED;

				DB::begin();
				$res = $station->save();

				if ( !$res ) {
					$this->addErrors( $station->getErrors() );
					throw new \Exception( "Station save error", ExceptionHelper::ERROR_GENERAL );
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
}
