<?php

namespace frontend\models;

use code\helpers\DB;
use common\models\MeasurementInterval;
use common\models\Station;
use Yii;
use code\helpers\ExceptionHelper;
use code\helpers\Flash;
use code\helpers\Log;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StationType;

/**
 * StationTypeSearchForm represents the model behind the search form of `common\models\StationType`.
 */
class ChartForm extends Model {
	public $id_station;
	public $id_measurement_interval;
	public $date_from;
	public $date_to;

	/**
	 * {@inheritdoc}
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[ [ 'id_station', 'id_measurement_interval' ], 'integer', 'min' => 1 ],
			[ 'id_station', 'default', 'value' => 8 ],
			[ 'id_measurement_interval', 'default', 'value' => 2 ],
			[ [ 'id_station', 'id_measurement_interval' ], 'required' ],
			[ [ 'date_from', 'date_to' ], 'date', 'format' => 'php:Y-m-d' ],
			[ 'date_from', 'default', 'value' => '2018-07-17' ],
			[ 'date_to', 'default', 'value' => '2018-07-23' ]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'id_station' => 'Станция',
			'id_measurement_interval' => 'Интервал измерений',
			'date_from' => 'Начало',
			'date_to' => 'Конец'
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
	 * @param array $data
	 * @param null $formName
	 * @return bool
	 */
	public function load( $data, $formName = null ) {
		$res = parent::load( $data, $formName );

		if ( Yii::$app->request->isPost ) {
			if ( array_key_exists( 'id_station', Yii::$app->request->post() ) )
				$this->id_station = (int)Yii::$app->request->post()[ 'id_station' ];
			if ( array_key_exists( 'id_measurement_interval', Yii::$app->request->post() ) )
				$this->id_measurement_interval = (int)Yii::$app->request->post()[ 'id_measurement_interval' ];
			if ( array_key_exists( 'date_from', Yii::$app->request->post() ) )
				$this->date_from = Yii::$app->request->post()[ 'date_from' ];
			if ( array_key_exists( 'date_to', Yii::$app->request->post() ) )
				$this->date_to = Yii::$app->request->post()[ 'date_to' ];
		}

		return $res;
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
	 * Returns measurements data
	 * @return bool
	 * @throws \Throwable
	 */
	public function GetData() {
		$res = false;

		$res[ 'columns' ] = [
			[ 'name' => 'measurement_time', 'type' => 'date' ],
			[ 'name' => 'pf_30_1', 'type' => 'number' ],
			[ 'name' => 'pf_50_1', 'type' => 'number' ],
			[ 'name' => 'pf_120_1', 'type' => 'number' ],
			[ 'name' => 'pf_30_2', 'type' => 'number' ],
			[ 'name' => 'pf_50_2', 'type' => 'number' ],
			[ 'name' => 'pf_120_2', 'type' => 'number' ]
		];

		$sqlstr = "select
  to_char( date_trunc( 'minute', measurement_time + interval '30 sec' ), 'YYYY-MM-DD HH24:MI' ) as measurement_time, pf_30_1, pf_50_1, pf_120_1, pf_30_2, pf_50_2, pf_120_2
from lysimetric_station_measurements
where
	id_station = :id_station
	and id_measurement_interval = :id_measurement_interval
	and measurement_time >= :date_from
	and measurement_time < :date_to
order by measurement_time";

		$query = DB::query(
			$sqlstr,
			[
				'id_station' => $this->id_station,
				'id_measurement_interval' => $this->id_measurement_interval,
				'date_from' => $this->date_from,
				'date_to' => $this->date_to
			]
		);

		foreach ( $query as $row ) {
			$tmp = [];
			foreach ( $row as $key => $value ) {
				$tmp[] = $value;
			}
			$res[ 'rows' ][] = $tmp;
		}

		return $res;
	}
}
