<?php

namespace common\models;

use Yii;
use backend\models\User;
/**
 * This is the model class for table "{{%TicketOrder}}".
 *
 * @property int $TicketID
 * @property string $Token
 * @property int $UserID
 * @property int $Operator
 * @property int $OrderID
 * @property int $OrderStatus
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 *
 * @property TicketChat[] $ticketChats
 * @property User $operator
 * @property Employee $user
 */
class TicketOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%TicketOrder}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Token', 'UserID'], 'required'],
            [['UserID', 'Operator', 'OrderID', 'OrderStatus', 'IsDelete'], 'integer'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['Token'], 'string', 'max' => 100],
            [['Operator'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['Operator' => 'id']],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['UserID' => 'EmployeeId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'TicketID' => Yii::t('app', 'Ticket I D'),
            'Token' => Yii::t('app', 'Token'),
            'UserID' => Yii::t('app', 'User I D'),
            'Operator' => Yii::t('app', 'Operator'),
            'OrderID' => Yii::t('app', 'Order I D'),
            'OrderStatus' => Yii::t('app', 'Order Status'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketChats()
    {
        return $this->hasMany(TicketChat::className(), ['TicketID' => 'TicketID']);
    }
	public function getLastTicketChats()
    {
        return $this->hasOne(TicketChat::className(), ['TicketID' => 'TicketID'])->orderBy(['TicketChatID' => SORT_DESC]);
    }
	

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperator()
    {
        return $this->hasOne(User::className(), ['id' => 'operator']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Employee::className(), ['EmployeeId' => 'UserID']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\TicketOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\TicketOrderQuery(get_called_class());
    }
}
