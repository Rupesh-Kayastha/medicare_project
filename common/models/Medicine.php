<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%Medicine}}".
 *
 * @property int $MedicineId
 * @property string $Name
 * @property int $BrandId
 * @property string $MedicineCategoryId
 * @property double $RegularPrice
 * @property double $DiscountedPrice
 * @property int $IsPrescription Prescription Required
 * @property int $InStock
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 *
 * @property Brand $brand
 * @property MedicineCategoryMaping[] $medicineCategoryMapings
 */
class Medicine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%Medicine}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'BrandId', 'RegularPrice', 'DiscountedPrice'], 'required'],
            [['BrandId', 'IsPrescription', 'InStock', 'BestSeller', 'IsDelete'], 'integer'],
            [['MedicineCategoryId'], 'string'],
            [['RegularPrice', 'DiscountedPrice'], 'number'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['Name'], 'string', 'max' => 200],
            [['BrandId'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['BrandId' => 'BrandId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'MedicineId' => Yii::t('app', 'Medicine ID'),
            'Name' => Yii::t('app', 'Name'),
            'MediceneImage' => Yii::t('app', 'Medicene Image'),
            'BrandId' => Yii::t('app', 'Brand ID'),
            'MedicineCategoryId' => Yii::t('app', 'Medicine Category ID'),
            'RegularPrice' => Yii::t('app', 'Regular Price'),
            'DiscountedPrice' => Yii::t('app', 'Discounted Price'),
            'IsPrescription' => Yii::t('app', 'Prescription Required'),
            'InStock' => Yii::t('app', 'In Stock'),
            'BestSeller' => Yii::t('app', 'Best Seller'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['BrandId' => 'BrandId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedicineCategoryMapings()
    {
        return $this->hasMany(MedicineCategoryMaping::className(), ['MedicineId' => 'MedicineId']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\MedicineQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\MedicineQuery(get_called_class());
    }
	
	public function GetCategories(){
		$categoryMapName=array();
		
		foreach($this->medicineCategoryMapings as $categoryMap){
			
		
		$categoryMapName[]=$categoryMap->medicineCategory->name;
		}
		
				
		return $categoryMapName;
		
	}

     public function excelUpload($excel)
    {
        $im=explode(".", $excel->name);
        $ext = $im[1];
        if(strtolower($ext)=='xls' || strtolower($ext)=='xlsb' || strtolower($ext)=='xlsm' || strtolower($ext)=='xlsx')
        {
            $folder='/excelfile/';
            $basepath = Yii::getAlias('@storage');
            $uploadpath = $basepath.$folder;
            $ppp=Yii::$app->security->generateRandomString();
            $path = $uploadpath . $ppp.".{$ext}";
            //$this->MedicineId=$excel->name;
            //$this->OnDate=date('Y-m-d H:i:s');
            //$this->UpdateBy=Yii::$app->user->identity->UserID;
            $excel->saveAs($path);
            $x=$this->save();
            $file=$ppp.".{$ext}";
            return $file;
        }
        else
        {
            return 0;
        }
       
    }

    /* public function MedicinebulkAdd($Name,$BrandName,$MedicineCategory,$RegularPrice,$DiscountedPrice,$prescription,$stock)
    { 
        $brand=Brand::find()->where(['Name'=>$BrandName])->one();
        if(count($brand)>0)
        {
          $BrandId=$brand->BrandId;
        }
        else
        {
          $BrandModel=new Brand();
          $BrandModel->Name=$BrandName;
          $BrandModel->save();
          $BrandId=$BrandModel->BrandId;
        }
        $medicine = new Medicine();
        $medicine->Name=$Name;
        $medicine->BrandId=$BrandId; 
        $medicine->MedicineCategoryId=$MedicineCategory; 
        $medicine->RegularPrice=$RegularPrice;  
        $medicine->DiscountedPrice=$DiscountedPrice; 
        $medicine->IsPrescription=$prescription; 
        $medicine->InStock=$stock; 
        $medicine->OnDate=date('Y-m-d H:i:s'); 
        $medicine->save();
        }*/
    
    
}
