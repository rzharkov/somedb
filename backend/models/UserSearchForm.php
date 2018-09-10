<?php

namespace backend\models;

use code\helpers\DB;
use code\helpers\Flash;
use code\helpers\Log;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearchForm represents the model behind the search form of `common\models\User`.
 */
class UserSearchForm extends Model {
    public $id;
    public $email;
    public $username;
    public $status;
    public $crtime;
    public $chtime;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'id', 'status' ], 'integer' ],
            [ [ 'username', 'email', 'crtime', 'chtime' ], 'safe' ],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search( $params ) {
        $query = User::find();

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
            'status' => $this->status,
        ] );

        $query->andFilterWhere( [ 'ilike', 'username', $this->username ] )
            ->andFilterWhere( [ 'ilike', 'email', $this->email ] );

        if ( $this->crtime ) {
            $query->andWhere( "date_trunc( 'day', crtime ) = :crtime", [ 'crtime' => $this->crtime ] );
        }

        if ( $this->chtime ) {
            $query->andWhere( "date_trunc( 'day', chtime ) = :chtime", [ 'chtime' => $this->chtime ] );
        }

        return $dataProvider;
    }
}
