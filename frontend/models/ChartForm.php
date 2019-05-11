<?php

namespace frontend\models;

use code\helpers\DB;
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
	public $id_uploading;
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
			[ 'id_uploading', 'integer', 'min' => 1 ],
			[ 'id_uploading', 'default', 'value' => 123 ],
			[ ['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d' ]
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
			if ( array_key_exists( 'id_uploading', Yii::$app->request->post() ) )
				$this->id_uploading = Yii::$app->request->post()[ 'id_uploading' ];
			if ( array_key_exists( 'date_from', Yii::$app->request->post() ) )
				$this->date_from = Yii::$app->request->post()[ 'date_from' ];
			if ( array_key_exists( 'date_to', Yii::$app->request->post() ) )
				$this->date_to = Yii::$app->request->post()[ 'date_to' ];
		}

		return $res;
	}

	public function GetData() {
		$res = false;
		//var_dump( $params );
		//die();

		$res[ 'columns' ] = [
			[ 'name' => 'measurement_time', 'type' => 'date' ],
			[ 'name' => 'pf_30_1', 'type' => 'number' ],
			//[ 'name' => 'pf_30_2', 'type' => 'number' ],
			[ 'name' => 'pf_50_1', 'type' => 'number' ],
			//[ 'name' => 'pf_50_2', 'type' => 'number' ],
			[ 'name' => 'pf_120_1', 'type' => 'number' ],
			//[ 'name' => 'pf_120_2', 'type' => 'number' ]
		];

		$sqlstr = "select
  to_char( date_trunc( 'minute', measurement_time + interval '30 sec' ), 'YYYY-MM-DD HH24:MI' ) as measurement_time, pf_30_1, pf_50_1, pf_120_1
from lysimetric_station_measurements
where
id_uploading = {$this->id_uploading}
order by measurement_time
limit 20";

		$query = DB::query( $sqlstr );

		foreach ( $query as $row ) {
			$tmp = [];
			foreach ( $row as $key => $value ) {
				$tmp[] = $value;
			}
			$res[ 'rows' ][] = $tmp;
		}

		//$res[ 'rows' ] = $query;

		return $res;
	}
}
