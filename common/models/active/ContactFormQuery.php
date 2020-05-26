<?php

namespace common\models\active;

/**
 * This is the ActiveQuery class for [[\common\models\ContactForm]].
 *
 * @see \common\models\ContactForm
 */
class ContactFormQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\ContactForm[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ContactForm|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
