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
  measurement_time, id, pf_30_1, pf_30_2, pf_50_1, pf_50_2
from lysimetric_station_measurements
where
id_uploading = 123
limit 10";

        $query = DB::query( $sqlstr );

        $res[] = [ 'measurement_time', 'pf_30_1' ];
        foreach( $query as $row ) {
            $res[] = [ $row['measurement_time'], (float)$row['pf_30_1'] ];
        }

        return $res;
    }
}
