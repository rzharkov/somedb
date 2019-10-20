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
	private $station;
	private $station_type;


	/**
	 * {@inheritdoc}
	 */
	function __construct() {
		parent::__construct();
		$this->id_station = ChartForm::GetDefault_id_station();
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[ [ 'id_station', 'id_measurement_interval' ], 'integer', 'min' => 1 ],
			[ 'id_station', 'default', 'value' => ChartForm::GetDefault_id_station() ],
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
		$res = [];

		foreach ( $this->GetAvailableFieldsList() as $key => $value ) {
			if ( $key != 'measurement_time' ) {
				$res[ $key ] = $key . ' ( ' . $value[ 'unit' ] . ' ) ';
			}
		}

		return $res;
	}

	/**
	 * Returns list of all possible fields for a current station
	 * @return array
	 */
	public function GetAvailableFieldsList() {
		$res = [];

		foreach ( $this->station_type->data_format as $data_item ) {
			$res[ $data_item[ 'column_name' ] ] = [
				'name' => $data_item[ 'column_name' ],
				'type' => 'number',
				'description' => $data_item[ 'description' ],
				'unit' => $data_item[ 'unit' ]
			];
		}

		return $res;
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
			$res[] = $tmp_list[ 'pf_30_1' ];
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

		$this->station = Station::findById( $this->id_station );
		$this->station_type = StationType::findById( $this->station->id_type );

		return $res;
	}

	public static function GetDefault_id_station() {
		$query = DB::query( "select min(id) as id from stations" );
		$id_station = $query[0]['id'];
		if ( $id_station === null ) {
			throw new \Exception( "No stations found. Please, add them using admin backend.", ExceptionHelper::STATION_NOT_FOUND );
		}
		return $id_station;
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

		$tmp[] = [ 'name' => 'measurement_time', 'type' => 'date' ];
		$tmp = array_merge( $tmp, $this->GetChosenFieldsList() );

		//after an each column add a tooltip
		foreach ( $tmp as $item ) {
			$res[ 'columns' ][] = $item;
			if ( $item[ 'name' ] != 'measurement_time' ) {
				$res[ 'columns' ][] = [ 'name' => $item[ 'name' ] . '_tooltip', 'type' => 'string', 'role' => 'tooltip' ];
			}
		}

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
			$i = 0;
			$first_column_flag = true;
			$tmp_measurement_time = $row[ 'measurement_time' ];
			foreach ( $row as $key => $value ) {
				$tmp[] = $value;
				if ( $first_column_flag ) { //add tooltip for the point
					$first_column_flag = false;
				} else {
					$tmp[] = "<nobr>{$tmp_measurement_time}</nobr><br/><b>{$value}</b>&nbsp;{$this->GetChosenFieldsList()[$i]['unit']}<br/>{$this->GetChosenFieldsList()[$i]['description']}";
					$i++;
				}
			}
			$res[ 'rows' ][] = $tmp;
		}

		return $res;
	}
}
