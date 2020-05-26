<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\MedicineCategory;
use common\models\MedicineCategoryMaping;
use common\models\CartItem;
use common\models\Cart;
use common\models\Medicine;
use common\models\Brand;
use yii\data\Pagination;
use yii\base\Widget;
use yii\widgets\LinkPager;

/**
 * Category controller
 */
class CategoryController extends Controller
{
	
	private $_CurrentCategory;
	public $categorySidebar=false;
	public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
	
	public function actionIndex($id=false)
    {
		
        if(is_numeric($id)){
			
			$data=[];
			$medicineitem = array();
			$category=MedicineCategory::findOne(['id'=>$id]);
			$this->_CurrentCategory=$category;
			$leaves = $category->leaves()->all();
			$data['CategoryFiletrs']=[];
			/****** Category Wise Products ********/

			$allproduct = MedicineCategoryMaping::find()->where(['MedicineCategoryId'=>$id]);
			$countQuery = clone $allproduct;
			
	        $pages = new Pagination(['totalCount' => $countQuery->count()]);
	        //$models = $allproduct->offset($pages->offset)->limit($pages->limit)->all();


			$allproduct = MedicineCategoryMaping::find()->where(['MedicineCategoryId'=>$id])->offset($pages->offset)->limit($pages->limit)->orderBy(['MedicineId' => SORT_DESC])->all();
			if($allproduct){
				foreach($allproduct as $key => $value){
					
					$medicineitem[$key]['MedicineName'] = $value->medicine->Name;
					$medicinecategory = MedicineCategory::find()->where(['id'=>$value->MedicineCategoryId])->one();
					$medicineitem[$key]['MedicineCategoyDesc'] = $medicinecategory->description;
					$medicineitem[$key]['MedicineId'] = $value->medicine->MedicineId;
					$medicineitem[$key]['BrandId'] = $value->medicine->BrandId;
					$medicinecategory = Brand::find()->where(['BrandId'=>$value->medicine->BrandId])->one();
					$medicineitem[$key]['BrandName'] = $medicinecategory->Name;
					$medicineitem[$key]['RegularPrice'] = $value->medicine->RegularPrice;
				
					if ($value->medicine->MediceneImage !='') {
						$getimage = $value->medicine->MediceneImage;
					}else{
						$getimage = Yii::getAlias('@storageUrl')."/default/medical.png";
					}
					$medicineitem[$key]['MedicineImage']=$getimage;
					$medicineitem[$key]['DiscountedPrice'] = $value->medicine->DiscountedPrice;
					$medicineitem[$key]['IsPrescription'] = $value->medicine->IsPrescription;
					$medicineitem[$key]['InStock'] = $value->medicine->InStock;
					$medicineitem[$key]['BestSeller'] = $value->medicine->BestSeller;
					$medicineitem[$key]['MedicineCategoryId'] = $id;
				}
			}
			

			$data['medicineitem']=$medicineitem;
			if(Yii::$app->session->get('CartIdentifire')!=""){
				$CartIdentifire = Yii::$app->session->get('CartIdentifire');
				$check_CartIdentifire = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
				if($check_CartIdentifire){
					$cartitems = CartItem::find()->where(['CartIdentifire'=>$CartIdentifire])->all();
					$itemcount = count($cartitems);
				}else{
					$itemcount = 0;
				}
			}else{
				$itemcount = 0;
			}
			$data['itemcount']=$itemcount;
			
			
			
			return $this->render('index',['data'=>$data,'pages' => $pages]);
		}
		else
		{
			return $this->redirect(['site/error']);
		}
    }
	
	public function getCurrentCategory(){
           return $this->_CurrentCategory;
    }
	
	public function getBreadcrumbs(){
		$Breadcrumbs=array();
		$Breadcrumbs[]="<a href=".Url::home()."><i class='fa fa-home'></i></a>";
		$currentCategory = MedicineCategory::findOne(['id' => $this->_CurrentCategory->id]);
		$parents = $currentCategory->parents()->all();
		
		foreach($parents as $parent){			
			if($parent->lvl){
			$Breadcrumbs[]="<a href=".Url::toRoute(['category/'.$parent->id]).">".$parent->name."</a>";
			}
		}
		
		$Breadcrumbs[]="<a href=".Url::toRoute(['category/'.$this->_CurrentCategory->id]).">".$this->_CurrentCategory->name."</a>";
		
		return implode("",$Breadcrumbs);
		
	}
	
	public  function getCategoryFiletrs(){
		
		$currentCategory = MedicineCategory::findOne(['id' => $this->_CurrentCategory->id]);
		if($currentCategory->lvl==1){
		$currentOpen=$currentCategory->id;	
		}
		else{
			$AllParents = $currentCategory->parents()->all();
			$TopParent =$AllParents[1];
			$currentOpen = $TopParent->id;
		}
		
		
		$roots = MedicineCategory::find()->roots()->addOrderBy('root, lft')->one();
			
		$childs=$this->getCategoryFiletrsInline($roots->children()->all(),$currentOpen);
		$First=$childs[$currentOpen];
		unset($childs[$currentOpen]);
		return array_merge(array($currentOpen=>$First),$childs);
	}


	public  function actionSearch(){
			$data=[];
			$getmedicene = array();
		 if (Yii::$app->request->post())
        {
        $find=Yii::$app->request->post()['find'];
        $medicene=Medicine::find()->where(['Medicine.IsDelete'=>0])
        ->andFilterwhere(['or',['LIKE', 'Medicine.Name', $find],['LIKE', 'Brand.Name', $find]])
        ->joinWith(['brand'])->all();
        if($medicene){
		foreach($medicene as $key => $value){
			
			$getmedicene[$key]['MedicineName'] = $value->Name;
			/*$medicinecategory = MedicineCategory::find()->where(['id'=>$value->MedicineCategoryId])->one();
			$getmedicene[$key]['MedicineCategoyDesc'] = $medicinecategory->description;*/
			$getmedicene[$key]['MedicineId'] = $value->MedicineId;
			$getmedicene[$key]['BrandId'] = $value->BrandId;
			$medicinecategory = Brand::find()->where(['BrandId'=>$value->BrandId])->one();
			$getmedicene[$key]['BrandName'] = $value->brand->Name;
			$getmedicene[$key]['RegularPrice'] = $value->RegularPrice;
		
			if ($value->MediceneImage !='') {
				$getimage = $value->MediceneImage;
			}else{
				$getimage = Yii::getAlias('@storageUrl')."/default/medical.png";
			}
			$getmedicene[$key]['MedicineImage']=$getimage;
			$getmedicene[$key]['DiscountedPrice'] = $value->DiscountedPrice;
			$getmedicene[$key]['IsPrescription'] = $value->IsPrescription;
			$getmedicene[$key]['InStock'] = $value->InStock;
			$getmedicene[$key]['MedicineCategoryId'] = $value->MedicineCategoryId;
		}
        //echo "<pre>";var_dump($medicene);
       
    	}
    	 $data['getmedicene']=$getmedicene;
			if(Yii::$app->session->get('CartIdentifire')!=""){
				$CartIdentifire = Yii::$app->session->get('CartIdentifire');
				$check_CartIdentifire = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
				if($check_CartIdentifire){
					$cartitems = CartItem::find()->where(['CartIdentifire'=>$CartIdentifire])->all();
					$itemcount = count($cartitems);
				}else{
					$itemcount = 0;
				}
			}else{
				$itemcount = 0;
			}
			$data['itemcount']=$itemcount;

		return $this->render('search',['data'=>$data,'find'=>$find]);
		
	}
	}
	private function getCategoryFiletrsInline($categories,$currentOpen=null, $left = 0, $right = null, $lvl = 1){
		
		
		$names=[];

		foreach ($categories as $index => $category) {
			
			if ($category->lft >= $left + 1 && (is_null($right) || $category->rgt <= $right) && $category->lvl == $lvl) {
				
				if($category->active){
					
					$node=[];
					
					
					
					
						
						
					
						$node['url']=['category/'.$category->id];
						$node['label']=$category->name;// $category->id.$this->_CurrentCategory->id;
						if(!is_null($currentOpen) && $currentOpen== $this->_CurrentCategory->id){
							$node['options'] = ['class'=>'open'];
							//$node['active'] = true;
						}
						
						if($category->id == $this->_CurrentCategory->id){
							//$node['active'] = true;
						}	
						
						if($currentOpen == $category->id){
						$childs = $this->getCategoryFiletrsInline($categories,true, $category->lft, $category->rgt, $category->lvl + 1);
						$node['items'] = $childs;
						}
						
						
						$names[$category->id]= $node;
				}
			}
		}
		return $names;
		
		
	}

}

?>