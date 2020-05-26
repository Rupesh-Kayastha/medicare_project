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
        $currentPage=Yii::$app->request->post('currentpage');
        $perpage=15;
        $offset=(($currentPage*$perpage)+1)-$perpage;
        $results=array();
        $data=array();
        $arr=array();
        $total_product=MedicineCategoryMaping::find()->with(['medicine','medicineCategory','medicine.brand'])->where(['MedicineCategoryId'=>$categoryid])->count();
        $allproduct=MedicineCategoryMaping::find()->with(['medicine','medicineCategory','medicine.brand'])->where(['MedicineCategoryId'=>$categoryid])->limit($perpage)->offset($offset)->asArray()->all();
        
        
        if(count($allproduct)>0)
        {
            foreach($allproduct as $key=>$value)
             {
               
               if(isset($value['medicine']))
               {
                 $data['MedicineId']=$value['MedicineId'];
                 $data['Medicinename']=$value['medicine']['Name'];
                 $data['Categoryname']=$value['medicineCategory']['name'];
                 $data['Brandname']=$value['medicine']['brand']['Name'];
                 $data['RegularPrice']=$value['medicine']['RegularPrice'];
                 $data['DiscountedPrice']=$value['medicine']['DiscountedPrice'];
                 $data['IsPrescription']=$value['medicine']['IsPrescription'];
                 $data['InStock']=$value['medicine']['InStock'];
                 
                 array_push($arr,$data);
               }
               
             }
        
        }
		$Category = MedicineCategory::findOne(['id' => $categoryid]);
		$SubCategories = $Category->children(1)->all();
        $subcat=[];
        
        foreach($SubCategories as $key=>$sc){
            
            if($sc->IncludeInMenu && $sc->active)
            $subcat[]=$sc;   
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
         if($total_product){
              $TotalPage=ceil($total_product/$perpage);
              $NextPage=$currentPage+1;
              $PrevPage=$currentPage-1;
              
              if($currentPage==$TotalPage)
              $NextPage=False;
              if($currentPage==1)
              $PrevPage=False;
              if($total_product<=$perpage)
                  $TotalPage=0;
         }
         else{
             
              $TotalPage=0;
              $NextPage=False;
              $PrevPage=False;
         }
        
         $results['status']=$status;
         $results['message']=$msg;
         $results['data']['products']=$arr;
		 $results['data']['categories']=$subcat;
         $results['data']['TotalProducts']=$total_product;
         $results['data']['TotalPage']=$TotalPage;
         $results['data']['CurrentPage']=$currentPage;
         $results['data']['NextPage']=$NextPage;
         $results['data']['PrevPage']=$PrevPage;
         $this->sendResponce($results);
    
   }
    
}
