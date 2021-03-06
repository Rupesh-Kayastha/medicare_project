<?php

namespace common\models\active;

/**
 * This is the ActiveQuery class for [[\common\models\TicketCartMap]].
 *
 * @see \common\models\TicketCartMap
 */
class TicketCartMapQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\TicketCartMap[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\TicketCartMap|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
