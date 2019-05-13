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
	public $visible_fields;
	public $visible_fields2;

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
			[ 'date_from', 'default', 'value' => '2018-07-21' ],
			[ 'date_to', 'default', 'value' => '2018-07-22' ],
			[ 'visible_fields', 'in', 'range' => array_keys( $this->GetVisibleFieldsList() ), 'allowArray' => true ],
			[ 'visible_fields2', 'in', 'range' => array_keys( $this->GetVisibleFieldsList() ), 'allowArray' => true ]
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
			'date_to' => 'Конец',
			'visible_fields' => 'Отображать поля',
			'visible_fields2' => 'Отображать поля ( правая ось )'
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
	 * Returns list of all possible visible fields for a current station
	 * @return array
	 */
	public function GetVisibleFieldsList() {
		return [
			'pf_30_1' => 'pf_30_1',
			'pf_30_1_min' => 'pf_30_1_min',
			'pf_30_1_max' => 'pf_30_1_max',
			'pf_30_2' => 'pf_30_2',
			'pf_30_2_min' => 'pf_30_2_min',
			'pf_30_2_max' => 'pf_30_2_max',
			'vac_30' => 'vac_30',
			'vac_30_min' => 'vac_30_min',
			'vac_30_max' => 'vac_30_max',
			'pf_50_1' => 'pf_50_1',
			'pf_50_1_min' => 'pf_50_1_min',
			'pf_50_1_max' => 'pf_50_1_max',
			'pf_50_2' => 'pf_50_2',
			'pf_50_2_min' => 'pf_50_2_min',
			'pf_50_2_max' => 'pf_50_2_max',
			'vac_50' => 'vac_50',
			'vac_50_min' => 'vac_50_min',
			'vac_50_max' => 'vac_50_max',
			'pf_120_1' => 'pf_120_1',
			'pf_120_1_min' => 'pf_120_1_min',
			'pf_120_1_max' => 'pf_120_1_max',
			'pf_120_2' => 'pf_120_2',
			'pf_120_2_min' => 'pf_120_2_min',
			'pf_120_2_max' => 'pf_120_2_max',
			'vac_120' => 'vac_120',
			'vac_120_min' => 'vac_120_min',
			'vac_120_max' => 'vac_120_max',
			'moisture_30_1' => 'moisture_30_1',
			'moisture_30_2' => 'moisture_30_2',
			'moisture_50_1' => 'moisture_50_1',
			'moisture_50_2' => 'moisture_50_2',
			'moisture_120_1' => 'moisture_120_1',
			'moisture_120_2' => 'moisture_120_2',
			'e_conductivity_30_1' => 'e_conductivity_30_1',
			'e_conductivity_30_2' => 'e_conductivity_30_2',
			'e_conductivity_50_1' => 'e_conductivity_50_1',
			'e_conductivity_50_2' => 'e_conductivity_50_2',
			'e_conductivity_120_1' => 'e_conductivity_120_1',
			'e_conductivity_120_2' => 'e_conductivity_120_2',
			't_30_1' => 't_30_1',
			't_30_2' => 't_30_2',
			't_50_1' => 't_50_1',
			't_50_2' => 't_50_2',
			't_120_1' => 't_120_1',
			't_120_2' => 't_120_2',
			'weight_1' => 'weight_1',
			'weight_2' => 'weight_2',
			'drain_1' => 'drain_1',
			'drain_1_min' => 'drain_1_min',
			'drain_1_max' => 'drain_1_max',
			'drain_2' => 'drain_2',
			'drain_2_min' => 'drain_2_min',
			'drain_2_max' => 'drain_2_max',
			'accu' => 'accu',
			'accu_min' => 'accu_min',
			'accu_max' => 'accu_max'
		];
	}

	/**
	 * Returns list of all possible fields for a current station
	 * @return array
	 */
	public function GetAvailableFieldsList() {
		return [
			'pf_30_1' => [ 'name' => 'pf_30_1', 'type' => 'number' ],
			'pf_30_1_min' => [ 'name' => 'pf_30_1_min', 'type' => 'number' ],
			'pf_30_1_max' => [ 'name' => 'pf_30_1_max', 'type' => 'number' ],
			'pf_30_2' => [ 'name' => 'pf_30_2', 'type' => 'number' ],
			'pf_30_2_min' => [ 'name' => 'pf_30_2_min', 'type' => 'number' ],
			'pf_30_2_max' => [ 'name' => 'pf_30_2_max', 'type' => 'number' ],
			'vac_30' => [ 'name' => 'vac_30', 'type' => 'number' ],
			'vac_30_min' => [ 'name' => 'vac_30_min', 'type' => 'number' ],
			'vac_30_max' => [ 'name' => 'vac_30_max', 'type' => 'number' ],
			'pf_50_1' => [ 'name' => 'pf_50_1', 'type' => 'number' ],
			'pf_50_1_min' => [ 'name' => 'pf_50_1_min', 'type' => 'number' ],
			'pf_50_1_max' => [ 'name' => 'pf_50_1_max', 'type' => 'number' ],
			'pf_50_2' => [ 'name' => 'pf_50_2', 'type' => 'number' ],
			'pf_50_2_min' => [ 'name' => 'pf_50_2_min', 'type' => 'number' ],
			'pf_50_2_max' => [ 'name' => 'pf_50_2_max', 'type' => 'number' ],
			'vac_50' => [ 'name' => 'vac_50', 'type' => 'number' ],
			'vac_50_min' => [ 'name' => 'vac_50_min', 'type' => 'number' ],
			'vac_50_max' => [ 'name' => 'vac_50_max', 'type' => 'number' ],
			'pf_120_1' => [ 'name' => 'pf_120_1', 'type' => 'number' ],
			'pf_120_1_min' => [ 'name' => 'pf_120_1_min', 'type' => 'number' ],
			'pf_120_1_max' => [ 'name' => 'pf_120_1_max', 'type' => 'number' ],
			'pf_120_2' => [ 'name' => 'pf_120_2', 'type' => 'number' ],
			'pf_120_2_min' => [ 'name' => 'pf_120_2_min', 'type' => 'number' ],
			'pf_120_2_max' => [ 'name' => 'pf_120_2_max', 'type' => 'number' ],
			'vac_120' => [ 'name' => 'vac_120', 'type' => 'number' ],
			'vac_120_min' => [ 'name' => 'vac_120_min', 'type' => 'number' ],
			'vac_120_max' => [ 'name' => 'vac_120_max', 'type' => 'number' ],
			'moisture_30_1' => [ 'name' => 'moisture_30_1', 'type' => 'number' ],
			'moisture_30_2' => [ 'name' => 'moisture_30_2', 'type' => 'number' ],
			'moisture_50_1' => [ 'name' => 'moisture_50_1', 'type' => 'number' ],
			'moisture_50_2' => [ 'name' => 'moisture_50_2', 'type' => 'number' ],
			'moisture_120_1' => [ 'name' => 'moisture_120_1', 'type' => 'number' ],
			'moisture_120_2' => [ 'name' => 'moisture_120_2', 'type' => 'number' ],
			'e_conductivity_30_1' => [ 'name' => 'e_conductivity_30_1', 'type' => 'number' ],
			'e_conductivity_30_2' => [ 'name' => 'e_conductivity_30_2', 'type' => 'number' ],
			'e_conductivity_50_1' => [ 'name' => 'e_conductivity_50_1', 'type' => 'number' ],
			'e_conductivity_50_2' => [ 'name' => 'e_conductivity_50_2', 'type' => 'number' ],
			'e_conductivity_120_1' => [ 'name' => 'e_conductivity_120_1', 'type' => 'number' ],
			'e_conductivity_120_2' => [ 'name' => 'e_conductivity_120_2', 'type' => 'number' ],
			't_30_1' => [ 'name' => 't_30_1', 'type' => 'number' ],
			't_30_2' => [ 'name' => 't_30_2', 'type' => 'number' ],
			't_50_1' => [ 'name' => 't_50_1', 'type' => 'number' ],
			't_50_2' => [ 'name' => 't_50_2', 'type' => 'number' ],
			't_120_1' => [ 'name' => 't_120_1', 'type' => 'number' ],
			't_120_2' => [ 'name' => 't_120_2', 'type' => 'number' ],
			'weight_1' => [ 'name' => 'weight_1', 'type' => 'number' ],
			'weight_2' => [ 'name' => 'weight_2', 'type' => 'number' ],
			'drain_1' => [ 'name' => 'drain_1', 'type' => 'number' ],
			'drain_1_min' => [ 'name' => 'drain_1_min', 'type' => 'number' ],
			'drain_1_max' => [ 'name' => 'drain_1_max', 'type' => 'number' ],
			'drain_2' => [ 'name' => 'drain_2', 'type' => 'number' ],
			'drain_2_min' => [ 'name' => 'drain_2_min', 'type' => 'number' ],
			'drain_2_max' => [ 'name' => 'drain_2_max', 'type' => 'number' ],
			'accu' => [ 'name' => 'accu', 'type' => 'number' ],
			'accu_min' => [ 'name' => 'accu_min', 'type' => 'number' ],
			'accu_max' => [ 'name' => 'accu_max', 'type' => 'number' ]
		];
	}

	/**
	 * Returns the list of chosen fields for display in a chart
	 * @return array
	 */
	public function GetChosenFieldsList() {
		$tmp_list = $this->GetAvailableFieldsList();
		$res = [];

		if ( is_array( $this->visible_fields ) ) {
			foreach ( $this->visible_fields as $field ) {
				$res[] = $tmp_list[ $field ];
			}
		} else {
			$res[] = $tmp_list['pf_30_1'];
		}

		return $res;
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
			if ( array_key_exists( 'visible_fields', Yii::$app->request->post() ) )
				$this->visible_fields = Yii::$app->request->post()[ 'visible_fields' ];
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

		$res[ 'columns' ][] = [ 'name' => 'measurement_time', 'type' => 'date' ];

		$res[ 'columns' ] = array_merge( $res[ 'columns' ], $this->GetChosenFieldsList() );

		$fields_list = '';
		$i = 0;
		foreach ( $this->GetChosenFieldsList() as $item ) {
			if ( strlen( $fields_list ) ) {
				$fields_list .= ', ';
			}
			$fields_list .= $item[ 'name' ] . ' as ' . $item[ 'name' ] . "{$i}"; //hack: query selects only once fields with the same names
			$i++;
		}

		$sqlstr = "select
  to_char( date_trunc( 'minute', measurement_time + interval '30 sec' ), 'YYYY-MM-DD HH24:MI' ) as measurement_time, {$fields_list}
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

		$res[ 'rows' ] = [];
		foreach ( $query as $row ) {
			$tmp = [];
			foreach ( $row as $key => $value ) {
				$tmp[] = $value;
			}
			$res[ 'rows' ][] = $tmp;
		}

		//var_dump( $res );

		return $res;
	}
}
