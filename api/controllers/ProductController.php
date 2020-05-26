<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use api\models\MedicineCategoryMaping;
use common\models\Medicine;

class ProductController extends BaseController
{
   
   
   
   public function actionSearchmedicine()
   {
        $medicinename=Yii::$app->request->post('medicinename');
        
        $results=array();
        $data=array();
        $arr=array();
        
        $alldata=Medicine::find()->where(['like','Name',$medicinename])->andWhere(['IsDelete'=>0])->all();
        if(count($alldata)>0)
        {
           foreach($alldata as $key=>$value)
           {
               if(isset($value->brand))
               {
                 $data['MedicineId']=$value->MedicineId;
                 $data['Medicinename']=$value->Name;
                 $data['Brandname']=$value->brand->Name;
                 $data['RegularPrice']=$value->RegularPrice;
                 $data['DiscountedPrice']=$value->DiscountedPrice;
                 $data['IsPrescription']=$value->IsPrescription;
                 $data['InStock']=$value->InStock;
                 
                 array_push($arr,$data);
               }
           }
        }
     
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
         $results['data']=$arr;
         $this->sendResponce($results);
   }
}
