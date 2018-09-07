<?php

namespace common\models;

use code\helpers\DB;
use code\helpers\Log;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class ProfileForm extends Model {
    public $username;
    public $email;
    public $password;
    public $new_password;
    public $retype_new_password;

    private $_user;


    function __construct() {
        parent::__construct();
        $this->username = \Yii::$app->user->identity->username;
        $this->email = \Yii::$app->user->identity->email;
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'password', 'new_password', 'retype_new_password' ], 'required' ],
            [ 'password', 'validatePassword' ],
            [ [ 'new_password', 'retype_new_password' ], 'validateNewPassword' ],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword( $attribute, $params ) {
        if ( !$this->hasErrors() ) {
            $user = $this->getUser();
            if ( !$user || !$user->validatePassword( $this->password ) ) {
                $this->addError( $attribute, 'Incorrect password.' );
            }
        }
    }

    public function validateNewPassword( $attribute, $params ) {
        if ( !$this->hasErrors() ) {
            if ( $attribute === "new_password" && strlen( $this->new_password ) < 5 ) {
                $this->addError( $attribute, 'New password is too short.' );
            }
            if ( $attribute === "retype_new_password" && strlen( $this->retype_new_password ) < 5 ) {
                $this->addError( $attribute, 'New password is too short.' );
            }
            if ( $attribute === "retype_new_password" && $this->retype_new_password !== $this->new_password ) {
                $this->addError( $attribute, 'New passwords does not match.' );
            }
            if ( $attribute === "new_password" && $this->new_password === $this->password ) {
                $this->addError( $attribute, 'Please enter new password.' );
            }
        }
    }

    /**
     * Finds user by [[id]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ( $this->_user === null ) {
            $this->_user = User::findById( \Yii::$app->user->id );
        }

        return $this->_user;
    }

    /**
     * Changes password for current user
     * @return bool
     */
    public function saveNewPassword() {
        try {
            if ( $this->validate() ) {
                $user = $this->getUser();
                $user->password = $this->new_password;

                DB::begin();
                $user->save();
                DB::commit();
                //DB::rollback();

                return true;
            } else {
                return false;
            }
        } catch ( \Throwable $e ) {
            DB::rollback();
            $this->addError( 'save_password_error', Log::getUserMessage( $e ) );
            return false;
        }
    }
}
