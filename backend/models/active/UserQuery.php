<?php

namespace backend\models\active;

/**
 * This is the ActiveQuery class for [[\backend\models\User]].
 *
 * @see \backend\models\User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \backend\models\User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \backend\models\User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
