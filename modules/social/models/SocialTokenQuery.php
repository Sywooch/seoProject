<?php

namespace app\modules\social\models;

/**
 * This is the ActiveQuery class for [[SocialToken]].
 *
 * @see SocialToken
 */
class SocialTokenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return SocialToken[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SocialToken|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
