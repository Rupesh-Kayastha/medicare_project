<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Company".
 *
 * @property int $CompanyId
 * @property string $Name
 * @property string $AddressLine1
 * @property string $AddressLine2
 * @property string $LandMark
 * @property string $State
 * @property string $City
 * @property int $Zipcode
 * @property int $ContactNo
 * @property string $Logo
 * @property int $ActiveStatus
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 *
 * @property Employee[] $employees
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'AddressLine1', 'AddressLine2', 'State', 'City', 'Zipcode', 'ContactNo'], 'required'],
            [['Zipcode', 'ContactNo', 'ActiveStatus', 'IsDelete'], 'integer'],
            [['Logo'], 'string'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['Name'], 'string', 'max' => 50],
            [['AddressLine1', 'AddressLine2', 'LandMark'], 'string', 'max' => 255],
            [['State', 'City'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CompanyId' => Yii::t('app', 'Company ID'),
            'Name' => Yii::t('app', 'Name'),
            'AddressLine1' => Yii::t('app', 'Address Line1'),
            'AddressLine2' => Yii::t('app', 'Address Line2'),
            'LandMark' => Yii::t('app', 'Land Mark'),
            'State' => Yii::t('app', 'State'),
            'City' => Yii::t('app', 'City'),
            'Zipcode' => Yii::t('app', 'Zipcode'),
            'ContactNo' => Yii::t('app', 'Contact No'),
            'Logo' => Yii::t('app', 'Logo'),
            'ActiveStatus' => Yii::t('app', 'Active Status'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['CompanyId' => 'CompanyId']);
    }

    public function getCompanyemiplan()
    {
        return $this->hasMany(EmiPlans::className(), ['EmiPlanCompanyId' => 'CompanyId']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\CompanyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\CompanyQuery(get_called_class());
    }
}
