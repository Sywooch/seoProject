<?php

namespace app\modules\social\models;

use Yii;
use app\models\User;
use nodge\eauth\ServiceBase;

/**
 * This is the model class for table "social_token".
 *
 * @property integer $id
 * @property string $service
 * @property integer $user_id
 * @property string $access_token
 * @property string $expires
 * @property string $refresh_token
 * @property string $params
 * @property string $sid
 *
 * @property User $user
 */
class SocialToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service', 'user_id', 'access_token','sid'], 'required'],
            [['user_id'], 'integer'],
            [['params'], 'string'],
            [['service'], 'string', 'max' => 32],
            [['access_token', 'expires', 'refresh_token'], 'string', 'max' => 250],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sid' => Yii::t('app', 'Social Id'),
            'service' => Yii::t('app', 'Service'),
            'user_id' => Yii::t('app', 'User ID'),
            'access_token' => Yii::t('app', 'Access Token'),
            'expires' => Yii::t('app', 'Expires'),
            'refresh_token' => Yii::t('app', 'Refresh Token'),
            'params' => Yii::t('app', 'Params'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return SocialTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SocialTokenQuery(get_called_class());
    }
 
    /**
     * Create social token from EAuth
     * 
     * @param \nodge\eauth\ServiceBase  $eauth
     * @param integer                   $userId
     * 
     * @return SocialToken
     */
    public static function createToken($eauth, $userId = null)
    {
        $model = new SocialToken();
        $token = $eauth->getAccessTokenData();
        $model->access_token = $token['access_token'];
        $model->expires = strval($token['expires']);
        $model->params = json_encode($token['params']);
        $model->refresh_token = $token['refresh_token'];
        $model->sid = $eauth->getId();
        if (is_null($userId)) {
            $model->user_id = Yii::$app->user->getId();    
        } else {
            $model->user_id = $userId;
        }
        $model->service = $eauth->getServiceName();
        if ($model->save()) {
            return $model;
        } 
        return null;
    }
    
    /**
     * Find Token by service
     * 
     * @param nodge\eauth\ServiceBase $service
     * 
     * @return SocialToken
     */
    public static function findByService($service)
    {
        $model = self::find()->with('user')->where([
            'service' => $service->getServiceName(), 
            'sid' => $service->getId()]
        )->one();
        if (is_null($model) && !Yii::$app->user->isGuest) {
            $model = self::createToken($service);
        }
        return $model;
    }
}
