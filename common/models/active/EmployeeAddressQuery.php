<?php

namespace common\models\active;

/**
 * This is the ActiveQuery class for [[\common\models\EmployeeAddress]].
 *
 * @see \common\models\EmployeeAddress
 */
class EmployeeAddressQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\EmployeeAddress[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\EmployeeAddress|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
