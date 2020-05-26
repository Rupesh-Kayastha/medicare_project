<?php

namespace backend\controllers;

use Yii;
use common\models\Company;
use common\models\search\CompanySearch;
use common\models\Employee;
use common\models\search\EmployeeSearch;
use common\models\EmployeeRole;
use common\models\search\EmployeeRoleSearch;
use common\models\SMS;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
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
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
    	// $company=Company::find()->where(['IsDelete'=>1])->all();
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            // 'company' => $company,
        ]);
    }

    /**
     * Displays a single Company model.
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
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();
		
		$empmodel = new Employee();
		$empmodel->scenario = 'create';
		$contactnum=$password=$empid='';
		/*$sender='';
		$message='';
		$to='';*/
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			$empmodel->load(Yii::$app->request->post());
			$empmodel->CompanyId=$model->CompanyId;
			$empmodel->EmployeeRoleId=EmployeeRole::SUPPER_ADMIN;
			$empmodel->EmployeeName="Supper Admin";
			$empmodel->setPassword(Yii::$app->request->post()['Employee']['Password']);
			$empmodel->Dob=date("Y-m-d",time());
			$empmodel->BloodGroup="N/A";
            $empmodel->CreditBalance=0;
            $empmodel->CreditLimit=0;
            $empmodel->CreditOnHold=0;

			$empid=Yii::$app->request->post()['Employee']['EmpId'];
			$contactnum=Yii::$app->request->post()['Employee']['ContactNo'];
			$password=Yii::$app->request->post()['Employee']['Password'];
			/*$sender='Arundaya Medicare Pvt. Ptd.';
			$contactnum=Yii::$app->request->post()['Employee']['ContactNo'];
			$message="your password is". Yii::$app->request->post()['Employee']['Password'];*/
			if($empmodel->save())
			{
				$SMS= new SMS();
				$sendsm=$SMS->sendSms($contactnum,$password,$empid);
				if($sendsm){
						Yii::$app->session->setFlash('success', "Password has been sent to your mobile no.");
						return $this->redirect(['view', 'id' => $model->CompanyId]);
						}
					else{
						Yii::$app->session->setFlash('error', "Network Error in OTP Sending, Please Try Again Later.");
						//return $this->redirect(['create', 'id' => $model->CompanyId]);
					}
			}
			else{
				$error=[];
				
				foreach($empmodel->getErrors() as $attribute => $message){
					$errorMesage="";
					$errorMesage.="<strong>".$empmodel->getAttributeLabel($attribute)."</strong> :";
					$errorMesage.=implode(". ",$message);
					$error[]=$errorMesage;
				}
				Yii::$app->session->setFlash('error',Yii::t('company', implode("<br>",$error)));
				
				$this->findModel($model->CompanyId)->delete();
				$model->isNewRecord=true;
				
				return $this->render('create', [
					'model' => $model,
					'empmodel'=>$empmodel
				]);
			}
			
			
            
        } else {
			$error=[];
			
			foreach($model->getErrors() as $attribute => $message){
				$errorMesage="";
				$errorMesage.="<strong>".$model->getAttributeLabel($attribute)."</strong> :";
				$errorMesage.=implode(". ",$message);
				$error[]=$errorMesage;
			}
			if($error)
			Yii::$app->session->setFlash('error',Yii::t('company', implode("<br>",$error)));
            return $this->render('create', [
                'model' => $model,
				'empmodel'=>$empmodel
            ]);
        }
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$empmodel = $this->findSupperAdmin($id);
		$empmodel->scenario = 'updateCompany';
		$password=$empmodel->Password;
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			$empmodel->load(Yii::$app->request->post());
            $empmodel->CreditBalance=0;
            $empmodel->CreditLimit=0;
            $empmodel->CreditOnHold=0;
						
			if(Yii::$app->request->post()['Employee']['Password']!=""){
				$empmodel->setPassword(Yii::$app->request->post()['Employee']['Password']);
			}
			else{
				$empmodel->Password=$password;
				$empmodel->password_repeat=$password;
			}
						
           	
			if($empmodel->save())
			{
				Yii::$app->session->setFlash('success', "Company Updated Successfully");
				return $this->redirect(['view', 'id' => $model->CompanyId]);
			}
			else{
				
				$error=[];
				
				foreach($empmodel->getErrors() as $attribute => $message){
					$errorMesage="";
					$errorMesage.="<strong>".$empmodel->getAttributeLabel($attribute)."</strong> :";
					$errorMesage.=implode(". ",$message);
					$error[]=$errorMesage;
				}
				Yii::$app->session->setFlash('error',Yii::t('company', implode("<br>",$error)));
				
				
				return $this->render('upadte', [
					'model' => $model,
					'empmodel'=>$empmodel
				]);
			}
			
           
        } else {
			$error=[];
			
			foreach($model->getErrors() as $attribute => $message){
				$errorMesage="";
				$errorMesage.="<strong>".$model->getAttributeLabel($attribute)."</strong> :";
				$errorMesage.=implode(". ",$message);
				$error[]=$errorMesage;
			}
			if($error)
			Yii::$app->session->setFlash('error',Yii::t('company', implode("<br>",$error)));
		
			$empmodel->Password='';
			$empmodel->password_repeat='';
            return $this->render('update', [
                'model' => $model,
				'empmodel'=>$empmodel
            ]);
        }
    }


    /* private function send_sms($to,$sender,$message){
		$sms= '&to=' . $to . '&sender=' . $sender . '&message=' . $message;
		$ch = curl_init('http://sandeshlive.in/API/WebSMS/Http/v1.0a/index.php?username=djgadi1&password=Dj@10gdi&route_id=23&reqid=1&format={json|text}' . $sms);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		// echo $response;
		// die();
		curl_close($ch);
	}*/

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
		$model->IsDelete=1;
		$model->save();
		Employee::updateAll(['IsDelete' => 1], ['=', 'CompanyId', $model->CompanyId]);
        Yii::$app->session->setFlash('success', "Your message to display.");
        return $this->redirect(['index']);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findSupperAdmin($id)
    {
        if (($model = Company::findOne($id)) !== null) {
			
			if (($empModel = Employee::findOne(['CompanyId' => $model->CompanyId,'EmployeeRoleId'=>1])) !== null) {
				return $empModel;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
        } else {
			throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	/**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
			
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
