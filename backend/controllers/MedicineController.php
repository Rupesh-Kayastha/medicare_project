<?php

namespace backend\controllers;

use Yii;
use common\models\Brand;
use common\models\Medicine;
use common\models\MedicineCategoryMaping;
use common\models\search\MedicineSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\web\UploadedFile;

/**
 * MedicineController implements the CRUD actions for Medicine model.
 */
class MedicineController extends Controller
{
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['post'],
        ],
      ],
    ];
  }

    /**
     * Lists all Medicine models.
     * @return mixed
     */
    public function actionIndex()
    {
      $searchModel = new MedicineSearch();
      $searchModel->IsDelete=0;
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

      return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
      ]);
    }

    /**
     * Displays a single Medicine model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
      return $this->render('view', [
        'model' => $this->findModel($id),
      ]);
    }


    /**
     * Creates a new Medicine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
      $model = new Medicine();

      if ($model->load(Yii::$app->request->post())) {


       if(Yii::$app->request->post()['Medicine']['BrandId']!='')
       {
        $BrandId=Yii::$app->request->post()['Medicine']['BrandId'];  
      }
      else
      {
        $BrandName=$this->Sanitize(Yii::$app->request->post()['BrandName']);
        $checkBrand=Brand::find()->where(['Name'=>$BrandName])->one();

        if(count($checkBrand)>0)
        {
          $BrandId=$checkBrand->BrandId;
        }
        else
        {
          $BrandModel=new Brand();
          $BrandModel->Name=$BrandName;
          $BrandModel->save();
          $BrandId=$BrandModel->BrandId;
        }
      }

      $model->Name=$this->Sanitize(Yii::$app->request->post()['Medicine']['Name']);
      $model->BrandId=$BrandId;
      $model->MediceneImage=Yii::$app->request->post()['Medicine']['MediceneImage'];

      if($model->save())
      {
        foreach(explode(",",$model->MedicineCategoryId) as $categoryMap){

         $CategoryMaping=new MedicineCategoryMaping();
         $CategoryMaping->MedicineId=$model->MedicineId;
         $CategoryMaping->MedicineCategoryId=$categoryMap;
         $CategoryMaping->save();
       }

       Yii::$app->session->setFlash('success', "Created Succssfully.");
       return $this->redirect(['index']);
     }
     else
     {
      Yii::$app->session->setFlash('error', "Please Try again.");
      return $this->redirect(['create']);
    }


  } else {
   $Brands=Brand::find()->select(['Name as value', 'Name as  label','BrandId as id'])->where(['IsDelete'=>0])->asArray()->all();
   return $this->render('create', [
    'model' => $model,
    'Brands'=>$Brands,
  ]);
 }
}


    /**
     * Updates an existing Medicine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      $model = $this->findModel($id);

      if ($model->load(Yii::$app->request->post())) {


       if(Yii::$app->request->post()['Medicine']['BrandId']!='')
       {
        $BrandId=Yii::$app->request->post()['Medicine']['BrandId'];  
      }
      else
      {
        $BrandName=$this->Sanitize(Yii::$app->request->post()['BrandName']);
        $checkBrand=Brand::find()->where(['Name'=>$BrandName])->one();
        if(count($checkBrand)>0)
        {
          $BrandId=$checkBrand->BrandId;
        }
        else
        {
          $BrandModel=new Brand();
          $BrandModel->Name=$BrandName;
          $BrandModel->save();
          $BrandId=$BrandModel->BrandId;
        }
      }
      $model->Name=$this->Sanitize(Yii::$app->request->post()['Medicine']['Name']);
      $model->MediceneImage=Yii::$app->request->post()['Medicine']['MediceneImage'];
      $model->BrandId=$BrandId;

      if($model->save())
      {

        MedicineCategoryMaping::deleteAll('MedicineId = :MedicineId',[':MedicineId'=>$model->MedicineId]);


        foreach(explode(",",$model->MedicineCategoryId) as $categoryMap){

         $CategoryMaping=new MedicineCategoryMaping();
         $CategoryMaping->MedicineId=$model->MedicineId;
         $CategoryMaping->MedicineCategoryId=$categoryMap;
         $CategoryMaping->save();
       }

       Yii::$app->session->setFlash('success', "Updated Succssfully.");
       return $this->redirect(['index']);
     }
     else
     {
      Yii::$app->session->setFlash('error', "Please Try again.");
      $Brands=Brand::find()->select(['Name as value', 'Name as  label','BrandId as id'])->where(['IsDelete'=>0])->asArray()->all();
      $MedicineCategories=MedicineCategory::find()->select(['CategoryName as value', 'CategoryName as  label','MedicineCategoryId as id'])->where(['IsDelete'=>0])->asArray()->all();
      return $this->render('update', [
       'model' => $model,
       'Brands'=>$Brands,
     ]);
    }

  } else {
   $Brands=Brand::find()->select(['Name as value', 'Name as  label','BrandId as id'])->where(['IsDelete'=>0])->asArray()->all();
   return $this->render('update', [
    'model' => $model,
    'Brands'=>$Brands,
  ]);
 }
}

    /**
     * Deletes an existing Medicine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
      $model=$this->findModel($id);
      $model->IsDelete=1;
      $model->save();
      Yii::$app->session->setFlash('success', "Your message to display.");
      return $this->redirect(['index']);
    }

    /**
     * Finds the Medicine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Medicine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
      if (($model = Medicine::findOne($id)) !== null) {
        return $model;
      } else {
        throw new NotFoundHttpException('The requested page does not exist.');
      }
    }

    public function Sanitize($str){

      $str = preg_replace('/\s+/', ' ', $str);
      $str=trim($str);
      $str=ucwords(strtolower($str));
      return $str;
    }

    public function actionImportmedicines()
    {
      $model = new Medicine();

      if ($model->load(Yii::$app->request->post())) {
        $excelfile = UploadedFile::getInstance($model, 'MedicineId');
        if($excelfile!='')
        {
          $exceldata=$model->excelUpload($excelfile);
          if($exceldata)
          {
            $result=$this->actionImportdata($exceldata);
            if($result==1)
            {
              Yii::$app->session->setFlash('success', "Successfully Uploaded.");  
            }
            else
            {
             Yii::$app->session->setFlash('error', "Please try again.");  
           }

         }
         else
         {
          Yii::$app->session->setFlash('error', "Invalid file."); 
        }
      }
      else
      {
        Yii::$app->session->setFlash('error', "Please upload a file.");
      }

      return $this->redirect(['importmedicines']);
    }
    return $this->render('importmedicines',['model'=>$model]);
  }
  public function actionImportdata($exceldata)
  {
    $folder='/excelfile/';
    $basepath = Yii::getAlias('@storage');
    $path = $basepath.$folder;


    $inputFileType = 'Xlsx';
    $inputFileName=$path.$exceldata;
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet = $reader->load($inputFileName);


    $sheetcount = $spreadsheet->getSheetCount();
    for ($i = 0; $i < $sheetcount; $i++)
    {
      $sheet = $spreadsheet->getSheet($i);
      $sheetrows = $sheet->getHighestRow();
      for($row = 2; $row<=$sheetrows; $row++)
      {
        $Name = (string)$sheet->getCellByColumnAndRow(1,$row);
        $BrandName = (string)$sheet->getCellByColumnAndRow(2,$row);
        $MedicineCategory = (string)$sheet->getCellByColumnAndRow(3,$row);
        $RegularPrice = (string)$sheet->getCellByColumnAndRow(4,$row);
        $DiscountedPrice = (string)$sheet->getCellByColumnAndRow(5,$row);
        $prescription = (string)$sheet->getCellByColumnAndRow(6,$row);
        $stock = (string)$sheet->getCellByColumnAndRow(7,$row);

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

        if($medicine->save())
        {
          foreach(explode(",",$medicine->MedicineCategoryId) as $categoryMap){

            $CategoryMaping=new MedicineCategoryMaping();
            $CategoryMaping->MedicineId=$medicine->MedicineId;
            $CategoryMaping->MedicineCategoryId=$categoryMap;
            $CategoryMaping->save();
          }

                            /*Yii::$app->session->setFlash('success', "Created Succssfully.");
                            return $this->redirect(['index']);*/
                          }
                          /*else
                          {
                            Yii::$app->session->setFlash('error', "Please Try again.");
                            return $this->redirect(['create']);
                          }*/

                        }
                      }
            //die();
                      return 1;
                    }

    /* public function actionImportmedicines()
    {

        $val='';
        $model=new Medicine();
        if (Yii::$app->request->post())
        {

            $image=UploadedFile::getInstance($model, 'MedicineId');
          
            if($image!='')
            {
                 $fileend=explode(".", $image->name);
                $ext = end($fileend);
                $basepath=Yii::getAlias('@storage');
                Yii::$app->params['uploadPath'] = $basepath . '/excelfile/';
                $ppp=Yii::$app->security->generateRandomString();
                $path = Yii::$app->params['uploadPath'] . $ppp.".{$ext}";
                //$file=$imagemodel->excelUpload5($fileName);
                $objPHPExcel = new \PHPExcel();
                $excelPath=str_replace('web','',Yii::$app->request->BaseUrl);
                $excelPath=__DIR__;
                
                $excelPath=str_replace('controllers','',str_replace('web','',$excelPath));
               
                $objPHPExcel = \PHPExcel_IOFactory::load($excelPath.$path);
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                foreach($sheetData as $key=>$data)
                {
                 if($key>1 && $data["A"]!='')
                        {
                         $Name=$data["A"];
                         $BrandName=$data["B"];
                         $MedicineCategory = $data["C"];
                         $RegularPrice = $data["D"];
                         $DiscountedPrice = $data["E"];
                         $prescription = $data["F"];
                         $stock = $data["F"];
                        

                        $MedicineId = $model->MedicinebulkAdd($Name,$BrandName,$MedicineCategory,$RegularPrice,$DiscountedPrice,$prescription,$stock);
                        
                        $val="success";  
                        }   
                }
                
            }
                 if($val=="success")
                {
                    Yii::$app->session->setFlash('success', "Import successfully... ");
                    return $this->redirect(['medicine']);
                }
                else{
                    Yii::$app->session->setFlash('error', "There is some error please try again");
                    return $this->redirect(['importmedicines']); 
                }
            }
            return $this->render('importmedicines',['model'=>$model]);
          }	*/
        }
