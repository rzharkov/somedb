<?php

namespace backend\models;

use code\helpers\DB;
use code\helpers\ExceptionHelper;
use code\helpers\Flash;
use code\helpers\Log;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Uploading;

class UploadingSearchForm extends Model {
    const STATUS_DELETED = 3;
    const STATUS_ACTIVE = 1;

    public $id;
    public $name;
    public $status;
    public $crtime;
    public $id_type;
    public $address;
    public $timezone;
    public $comment;

    private $_uploading;

    /**
     * {@inheritdoc}
     *
     * UserSearchForm constructor.
     * @param null $id
     */
    function __construct( $id = null ) {
        parent::__construct();
        if ( $id !== null ) {
            $this->getUploading( $id );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'id', 'id_type', 'status' ], 'integer' ],
            [ [ 'name', 'address', 'comment', 'crtime', 'timezone' ], 'safe' ],
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
        $query = Uploading::find();

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
            'crtime' => $this->crtime,
        ] );

        $query->andFilterWhere( [ 'ilike', 'name', $this->name ] );
            //->andFilterWhere( [ 'ilike', 'address', $this->address ] )
            //->andFilterWhere( [ 'ilike', 'comment', $this->comment ] );

        return $dataProvider;
    }

    public function getUploading( $id ) {
        $this->_uploading = Uploading::findOne( $id );

        return $this->_uploading;
    }

    public function deleteUploading( $id ) {
        try {
            if ( $this->validate() ) {
                $uploading = Uploading::findById( $id );

                if ( !$uploading ) {
                    throw new \Exception( "user not found", ExceptionHelper::USER_NOT_FOUND );
                }

                $uploading->status = Uploading::STATUS_DELETED;

                DB::begin();
                $res = $uploading->save();

                if( !$res ) {
                    $this->addErrors( $uploading->getErrors() );
                    throw new \Exception( "User save error", ExceptionHelper::ERROR_GENERAL );
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
}
