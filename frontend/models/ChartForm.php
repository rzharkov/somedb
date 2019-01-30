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
            [ [ 'id', 'status' ], 'integer' ],
            [ [ 'measurements_table_name' ], 'string' ],
            [ [ 'name', 'crtime', 'measurements_table_name' ], 'safe' ],
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

    public function GetData( $params ) {
        $sqlstr = "select
  to_char( date_trunc( 'minute', measurement_time + interval '30 sec' ), 'YYYY-MM-DD HH:MI' ) as measurement_time, to_char( date_trunc( 'minute', measurement_time + interval '30 sec' ), 'HH:MI' ) as measurement_time_short, id, pf_30_1, pf_30_2, pf_50_1, pf_50_2, pf_120_1, pf_120_2
from lysimetric_station_measurements
where
id_uploading = 123
order by measurement_time
limit 150";

        $query = DB::query( $sqlstr );

        $res[] = [ 'measurement_time', 'pf_30_1', 'pf_50_1', 'pf_120_1' ];
        foreach( $query as $row ) {
            $res[] = [ $row['measurement_time_short'], (float)$row['pf_30_1'], (float)$row['pf_50_1'], (float)$row['pf_120_1'] ];
        }
        $res[1][0] = $query[0]['measurement_time'];
        $res[count( $res ) - 1][0] = $query[count( $query ) - 1]['measurement_time'];

        //$res[count($res) -1 ]['measurement_time'] = $query[count($res) - 2]['measurement_time_raw'];

        return $res;
    }
}
