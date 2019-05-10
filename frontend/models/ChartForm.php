<?php

namespace frontend\models;

use code\helpers\DB;
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
	public $id_upload;
	public $date_from;
	public $date_to;

	/**
	 * {@inheritdoc}
	 *
	 * UserSearchForm constructor.
	 * @param null $id
	 */
	function __construct( $id = null ) {
		parent::__construct();
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[ 'id_upload', 'integer', 'min' => 1 ],
			[ 'id_upload', 'default', 'value' => 123 ],
			[ [ 'date_from', 'date_to' ], 'safe' ]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
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
id_uploading = {$this->id_upload}
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
