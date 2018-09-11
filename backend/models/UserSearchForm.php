<?php

namespace backend\models;

use code\helpers\DB;
use code\helpers\ExceptionHelper;
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
    public $new_password;

    private $_user;

    /**
     * {@inheritdoc}
     *
     * UserSearchForm constructor.
     * @param null $id
     */
    function __construct( $id = null ) {
        parent::__construct();
        if ( $id !== null ) {
            $this->getUser( $id );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'id', 'status' ], 'integer' ],
            [ [ 'username', 'email', 'crtime', 'chtime', 'new_password' ], 'safe' ],
            [ [ 'new_password' ], 'validateNewPassword' ],
        ];
    }

    /**
     * Проверяет не коротковат ли новый пароль
     * @param $attribute
     * @param $params
     */
    public function validateNewPassword( $attribute, $params ) {
        if ( !$this->hasErrors() ) {
            if ( $attribute === "new_password" && strlen( $this->new_password ) < 5 ) {
                $this->addError( $attribute, 'New password is too short.' );
            }
        }
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

    public function getUser( $id ) {
        $this->_user = User::findOne( $id );

        $this->id = $this->_user->id;
        $this->email = $this->_user->email;
        $this->username = $this->_user->username;
        $this->status = $this->_user->status;
        $this->crtime = $this->_user->crtime;
        $this->chtime = $this->_user->chtime;

        return $this->_user;
    }

    public function updateUser( $id ) {
        try {
            if ( $this->validate() ) {
                $user = User::findById( $id );

                if ( !$user ) {
                    throw new \Exception( "user not found", ExceptionHelper::USER_NOT_FOUND );
                }

                $user->email = $this->email;
                $user->username = $this->username;
                $user->status = $this->status;

                if ( $this->new_password ) {
                    $user->setPassword( $this->new_password );
                }

                DB::begin();
                $res = $user->save();

                if( !$res ) {
                    $this->addErrors( $user->getErrors() );
                    throw new \Exception( "User save error", ExceptionHelper::ERROR_GENERAL );
                }

                DB::commit();

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

    public function createUser() {
        try {
            if ( $this->validate() ) {
                $user = new User();

                if ( !$user ) {
                    throw new \Exception( "user not found", ExceptionHelper::USER_NOT_FOUND );
                }

                $user->email = $this->email;
                $user->username = $this->username;
                $user->status = User::STATUS_ACTIVE;
                $user->setPassword( $this->new_password );
                $user->generateAuthKey();

                DB::begin();
                $res = $user->save();

                if( !$res ) {
                    $this->addErrors( $user->getErrors() );
                    throw new \Exception( "User save error", ExceptionHelper::ERROR_GENERAL );
                }

                $user->refresh();

                //DB::rollback();
                DB::commit();

                return $user->id;
            } else {
                return false;
            }
        } catch ( \Throwable $e ) {
            DB::rollback();
            $this->addError( Flash::ATTRIBUTE_SYSTEM, Log::getUserMessage( $e ) );
            return false;
        }
    }

    public function deleteUser( $id ) {
        try {
            if ( $this->validate() ) {
                $user = User::findById( $id );

                if ( !$user ) {
                    throw new \Exception( "user not found", ExceptionHelper::USER_NOT_FOUND );
                }

                $user->status = User::STATUS_DELETED;

                DB::begin();
                $res = $user->save();

                if( !$res ) {
                    $this->addErrors( $user->getErrors() );
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
