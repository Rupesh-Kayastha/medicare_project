<?php
namespace api\models;
use common\models\Medicine;
use Yii;

/**
 * This is the model class for table "{{%MedicineCategoryMaping}}".
 *
 * @property int $MedicineCategoryMapingId
 * @property int $MedicineId
 * @property int $MedicineCategoryId
 *
 * @property Medicine $medicine
 * @property MedicineCategory $medicineCategory
 */
class MedicineCategoryMaping extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%MedicineCategoryMaping}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['MedicineId', 'MedicineCategoryId'], 'required'],
            [['MedicineId', 'MedicineCategoryId'], 'integer'],
            [['MedicineId'], 'exist', 'skipOnError' => true, 'targetClass' => Medicine::className(), 'targetAttribute' => ['MedicineId' => 'MedicineId']],
            [['MedicineCategoryId'], 'exist', 'skipOnError' => true, 'targetClass' => MedicineCategory::className(), 'targetAttribute' => ['MedicineCategoryId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'MedicineCategoryMapingId' => Yii::t('app', 'Medicine Category Maping ID'),
            'MedicineId' => Yii::t('app', 'Medicine ID'),
            'MedicineCategoryId' => Yii::t('app', 'Medicine Category ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedicine()
    {
        return $this->hasOne(Medicine::className(), ['MedicineId' => 'MedicineId'])->where(['IsDelete'=>0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedicineCategory()
    {
        return $this->hasOne(MedicineCategory::className(), ['id' => 'MedicineCategoryId']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\MedicineCategoryMapingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\MedicineCategoryMapingQuery(get_called_class());
    }
}
