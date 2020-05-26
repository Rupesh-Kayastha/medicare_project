<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use api\models\MedicineCategory;
use api\models\MedicineCategoryMaping;
use common\models\Medicine;

class CategoryController extends BaseController
{
   
   public function actionAllcategory()
   {
      $results=array();
      $res=MedicineCategory::getFullTreeInline();
      if($res)
      {
         $status=1;
         $msg="Record Found.";
      }
      else
      {
         $status=0;
         $msg="No Record Found.";
      }
      
      $results['status']=$status;
      $results['message']=$msg;
      $results['data']=$res;
      $this->sendResponce($results);
   }
   public function actionCategoryproducts()
   {
	 
        $categoryid=Yii::$app->request->post('categoryid');
        
        $results=array();
        $data=array();
        $arr=array();
        
        $allproduct=MedicineCategoryMaping::find()->where(['MedicineCategoryId'=>$categoryid])->all();
        if(count($allproduct)>0)
        {
            foreach($allproduct as $key=>$value)
             {
               
               if(isset($value->medicine))
               {
                 $data['MedicineId']=$value->MedicineId;
                 $data['Medicinename']=$value->medicine->Name;
                 $data['Categoryname']=$value->medicineCategory->name;
                 $data['Brandname']=$value->medicine->brand->Name;
                 $data['RegularPrice']=$value->medicine->RegularPrice;
                 $data['DiscountedPrice']=$value->medicine->DiscountedPrice;
                 $data['IsPrescription']=$value->medicine->IsPrescription;
                 $data['InStock']=$value->medicine->InStock;
                 
                 array_push($arr,$data);
               }
               
             }
        }
		$Category = MedicineCategory::findOne(['id' => $categoryid]);
		$SubCategories = $Category->children(1)->all();
         if(!empty($arr))
         {
            $status=1;
            $msg='Record Found.';
         }
         else
         {
            $status=0;
            $msg='No Record Found.';
         }
        
         $results['status']=$status;
         $results['message']=$msg;
         $results['data']['products']=$arr;
		 $results['data']['categories']=$SubCategories;
         $this->sendResponce($results);
    
   }
    
}
