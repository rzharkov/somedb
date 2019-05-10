<?php

namespace backend\models;

use code\helpers\DB;
use code\helpers\ExceptionHelper;
use code\helpers\Flash;
use code\helpers\Log;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MeasurementInterval;

/**
 * MeasurementIntervalearchForm represents the model behind the search form of `common\models\MeasurementInterval`.
 */
class MeasurementIntervalSearchForm extends Model {
	const STATUS_DELETED = 3;
	const STATUS_ACTIVE = 1;

	public $id;
	public $name;
	public $status;
	public $crtime;

	private $_measurement_interval;

	/**
	 * {@inheritdoc}
	 *
	 * UserSearchForm constructor.
	 * @param null $id
	 */
	function __construct( $id = null ) {
		parent::__construct();
		if ( $id !== null ) {
			$this->getMeasurementInterval( $id );
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[ [ 'id', 'status' ], 'integer' ],
			[ [ 'name', 'crtime' ], 'safe' ],
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
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search( $params ) {
		$query = MeasurementInterval::find();

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
			'status' => $this->status,
		] );

		$query->andFilterWhere( [ 'ilike', 'name', $this->name ] );

		return $dataProvider;
	}

	public function getMeasurementInterval( $id ) {
		$this->_measurement_interval = MeasurementInterval::findOne( $id );

		$this->id = $this->_measurement_interval->id;
		$this->name = $this->_measurement_interval->name;
		$this->status = $this->_measurement_interval->status;

		return $this->_measurement_interval;
	}

	public function deleteMeasurementInterval( $id ) {
		try {
			if ( $this->validate() ) {
				$measurement_interval = MeasurementInterval::findById( $id );

				if ( !$measurement_interval ) {
					throw new \Exception( "measurement interval not found", ExceptionHelper::USER_NOT_FOUND );
				}

				$measurement_interval->status = $measurement_interval::STATUS_DELETED;

				DB::begin();
				$res = $measurement_interval->save();

				if ( !$res ) {
					$this->addErrors( $measurement_interval->getErrors() );
					throw new \Exception( "measurement interval save error", ExceptionHelper::ERROR_GENERAL );
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

	public function createMeasurementInterval() {
		try {
			if ( $this->validate() ) {
				$measurement_interval = new MeasurementInterval();

				if ( !$measurement_interval ) {
					throw new \Exception( "measurement interval not found", ExceptionHelper::USER_NOT_FOUND );
				}

				$measurement_interval->name = $this->name;

				DB::begin();
				$res = $measurement_interval->save();

				if ( !$res ) {
					$this->addErrors( $measurement_interval->getErrors() );
					throw new \Exception( "User save error", ExceptionHelper::ERROR_GENERAL );
				}

				$measurement_interval->refresh();

				//DB::rollback();
				DB::commit();

				return $measurement_interval->id;
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
