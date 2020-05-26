<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%TicketCartMap}}".
 *
 * @property int $TicketCartMapId
 * @property string $TicketOrderToken
 * @property string $CartIdentifire
 *
 * @property TicketOrder $ticketOrderToken
 * @property Cart $cartIdentifire
 */
class TicketCartMap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%TicketCartMap}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TicketOrderToken', 'CartIdentifire'], 'required'],
            [['TicketOrderToken', 'CartIdentifire'], 'string', 'max' => 100],
            [['TicketOrderToken'], 'exist', 'skipOnError' => true, 'targetClass' => TicketOrder::className(), 'targetAttribute' => ['TicketOrderToken' => 'Token']],
            [['CartIdentifire'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::className(), 'targetAttribute' => ['CartIdentifire' => 'CartIdentifire']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'TicketCartMapId' => Yii::t('app', 'Ticket Cart Map ID'),
            'TicketOrderToken' => Yii::t('app', 'Ticket Order Token'),
            'CartIdentifire' => Yii::t('app', 'Cart Identifire'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketOrderToken()
    {
        return $this->hasOne(TicketOrder::className(), ['Token' => 'TicketOrderToken']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCartIdentifire()
    {
        return $this->hasOne(Cart::className(), ['CartIdentifire' => 'CartIdentifire']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\TicketCartMapQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\TicketCartMapQuery(get_called_class());
    }
}
