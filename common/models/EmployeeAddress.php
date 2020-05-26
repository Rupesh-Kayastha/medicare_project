<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "EmployeeAddress".
 *
 * @property int $EmployeeAddressId
 * @property int $EmployeeId
 * @property string $AddressLine1
 * @property string $AddressLine2
 * @property string $LandMark
 * @property string $State
 * @property string $City
 * @property int $Zipcode
 * @property int $ContactNo
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 *
 * @property Employee $employee
 */
class EmployeeAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'EmployeeAddress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['EmployeeId', 'AddressLine1', 'AddressLine2', 'State', 'City', 'Zipcode', 'ContactNo'], 'required'],
            [['EmployeeId', 'Zipcode', 'ContactNo', 'IsDelete'], 'integer'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['AddressLine1', 'AddressLine2', 'LandMark'], 'string', 'max' => 255],
            [['State', 'City'], 'string', 'max' => 100],
            [['EmployeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['EmployeeId' => 'EmployeeId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'EmployeeAddressId' => Yii::t('app', 'Employee Address ID'),
            'EmployeeId' => Yii::t('app', 'Employee ID'),
            'AddressLine1' => Yii::t('app', 'Address Line1'),
            'AddressLine2' => Yii::t('app', 'Address Line2'),
            'LandMark' => Yii::t('app', 'Land Mark'),
            'State' => Yii::t('app', 'State'),
            'City' => Yii::t('app', 'City'),
            'Zipcode' => Yii::t('app', 'Zipcode'),
            'ContactNo' => Yii::t('app', 'Contact No'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['EmployeeId' => 'EmployeeId']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\EmployeeAddressQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\EmployeeAddressQuery(get_called_class());
    }
}
