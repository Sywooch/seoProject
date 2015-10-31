<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $url
 * @property string $meta_description
 * @property string $title
 * @property string $meta_keyword
 * @property string $content
 *
 * @property Company $company
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'url', 'meta_description', 'title', 'meta_keyword', 'content'], 'required'],
            [['company_id'], 'integer'],
            [['meta_description', 'content'], 'string'],
            [['url'], 'string', 'max' => 150],
            [['title', 'meta_keyword'], 'string', 'max' => 250],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'company_id' => Yii::t('app', 'Company ID'),
            'url' => Yii::t('app', 'Url'),
            'meta_description' => Yii::t('app', 'Meta Description'),
            'title' => Yii::t('app', 'Title'),
            'meta_keyword' => Yii::t('app', 'Meta Keyword'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['user_id' => 'company_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\query\PagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\PagesQuery(get_called_class());
    }
}
