<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "public.user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class User extends \yii\db\ActiveRecord {
    protected $_password;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'public.user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [ [ 'username', 'email' ], 'required' ],
            [ [ 'status', 'created_at', 'updated_at' ], 'default', 'value' => null ],
            [ [ 'status', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'username', 'email', 'password' ], 'string', 'max' => 255 ],
            [ [ 'email' ], 'unique' ],
            [ [ 'username' ], 'unique' ],
            [ [ 'password' ], 'validateNewPassword' ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'email' => 'Email',
            'status' => 'Status',
        ];
    }

    public function validateNewPassword( $attribute, $params ) {
        if ( !$this->hasErrors() ) {
            if ( $attribute === "password" && strlen( $this->password ) < 5 ) {
                $this->addError( $attribute, 'New password is too short.' );
            }
        }
    }

    public function setPassword( $password ) {
        $this->_password = $password;
        if ( strlen( $password ) > 0 ) {
            $this->password_hash = Yii::$app->security->generatePasswordHash( $password );
        }
    }

    public function getPassword() {
        return $this->_password;
    }

    public function beforeSave( $insert ) {
        if ( $this->validate() ) {
            $this->auth_key = $this->auth_key = Yii::$app->security->generateRandomString();
            $this->status = 10;
            $this->created_at = mktime();
            $this->updated_at = mktime();;
            return parent::beforeSave( $insert );
        } else {
            return false;
        }
    }
}
