<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%subscribe}}".
 *
 * @property integer $id
 * @property string $email
 * @property string $phone
 * @property integer $user_id
 * 
 * @property User $user
 */
class Subscribe extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscribe}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['user_id'], 'integer'],
//            [['phone'], 'udokmeci\yii2PhoneValidator\PhoneValidator'],
            [['email'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'user_id' => Yii::t('app', 'User'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\query\SubscribeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\SubscribeQuery(get_called_class());
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
