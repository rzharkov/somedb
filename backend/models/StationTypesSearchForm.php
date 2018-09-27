<?php

namespace backend\models;

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

        return $this->_user;
    }
}
