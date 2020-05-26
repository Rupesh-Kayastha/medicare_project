<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%OrderItems}}".
 *
 * @property int $OrderItemID
 * @property int $OrdersID
 * @property string $CartIdentifire
 * @property int $OrderMedicineID
 * @property string $OrderItemName
 * @property int $OrderItemQty
 * @property double $OrderItemPrice
 * @property double $OrderItemTotalPrice
 * @property int $IsPrescription
 * @property string $CreatedDate
 * @property string $UpdatedDate
 * @property int $IsDelete
 *
 * @property Cart $cartIdentifire
 * @property Medicine $orderMedicine
 * @property Orders $orders
 */
class OrderItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%OrderItems}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['OrdersID', 'CartIdentifire', 'OrderMedicineID'], 'required'],
            [['OrdersID', 'OrderMedicineID', 'OrderItemQty', 'IsPrescription', 'IsDelete'], 'integer'],
            [['OrderItemPrice', 'OrderItemTotalPrice'], 'number'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['CartIdentifire'], 'string', 'max' => 100],
            [['OrderItemName'], 'string', 'max' => 255],
            [['CartIdentifire'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::className(), 'targetAttribute' => ['CartIdentifire' => 'CartIdentifire']],
            [['OrderMedicineID'], 'exist', 'skipOnError' => true, 'targetClass' => Medicine::className(), 'targetAttribute' => ['OrderMedicineID' => 'MedicineId']],
            [['OrdersID'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['OrdersID' => 'OrderId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'OrderItemID' => Yii::t('app', 'Order Item I D'),
            'OrdersID' => Yii::t('app', 'Orders I D'),
            'CartIdentifire' => Yii::t('app', 'Cart Identifire'),
            'OrderMedicineID' => Yii::t('app', 'Order Medicine I D'),
            'OrderItemName' => Yii::t('app', 'Order Item Name'),
            'OrderItemQty' => Yii::t('app', 'Order Item Qty'),
            'OrderItemPrice' => Yii::t('app', 'Order Item Price'),
            'OrderItemTotalPrice' => Yii::t('app', 'Order Item Total Price'),
            'IsPrescription' => Yii::t('app', 'Is Prescription'),
            'CreatedDate' => Yii::t('app', 'Created Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCartIdentifire()
    {
        return $this->hasOne(Cart::className(), ['CartIdentifire' => 'CartIdentifire']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderMedicine()
    {
        return $this->hasOne(Medicine::className(), ['MedicineId' => 'OrderMedicineID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasOne(Orders::className(), ['OrderId' => 'OrdersID']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\OrderItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\OrderItemsQuery(get_called_class());
    }
}
