<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%Transaction}}".
 *
 * @property int $Id
 * @property string $UserName
 * @property string $UserMob
 * @property string $UserMail
 * @property double $amount
 * @property string $PaymentID
 * @property string $SecureHash
 * @property string $DateOfPayment
 * @property int $IsSuccess
 * @property int $IsDelete
 * @property string $TransactionId
 * @property string $MerchantID
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%Transaction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UserName', 'UserMob', 'UserMail', 'amount'], 'required'],
            //[['amount'], 'number'],
            [['DateOfPayment'], 'safe'],
            [['IsSuccess', 'IsDelete'], 'integer'],
            [['UserName', 'UserMob', 'UserMail', 'TransactionId'], 'string', 'max' => 50],
            [['PaymentID'], 'string', 'max' => 100],
            [['SecureHash', 'MerchantID'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('app', 'ID'),
            'EmpId' => Yii::t('app', 'Emp Id'),
            'UserName' => Yii::t('app', 'User Name'),
            'UserMob' => Yii::t('app', 'User Mob'),
            'UserMail' => Yii::t('app', 'User Mail'),
            'amount' => Yii::t('app', 'Amount'),
            'PaymentID' => Yii::t('app', 'Payment ID'),
            'SecureHash' => Yii::t('app', 'Secure Hash'),
            'DateOfPayment' => Yii::t('app', 'Date Of Payment'),
            'IsSuccess' => Yii::t('app', 'Is Success'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'TransactionId' => Yii::t('app', 'Transaction ID'),
            'MerchantID' => Yii::t('app', 'Merchant ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\TransactionQuery(get_called_class());
    }
}
