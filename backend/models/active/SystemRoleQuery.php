<?php

namespace backend\models\active;

/**
 * This is the ActiveQuery class for [[\backend\models\SystemRole]].
 *
 * @see \backend\models\SystemRole
 */
class SystemRoleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \backend\models\SystemRole[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \backend\models\SystemRole|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
