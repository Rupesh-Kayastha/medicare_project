<?php

namespace company\controllers;

use Yii;
use common\models\Employee;
use company\models\search\EmployeeSearch;
use common\models\EmployeeRole;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

use yii\helpers\ArrayHelper;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{
    public function behaviors()
    {
        return [
         'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => (Yii::$app->user->identity && Yii::$app->user->identity->EmployeeRoleId!=EmployeeRole::NORMAL_EMPLOYEEE),
                    'roles' => ['@'],
                ],
            ],
        ],
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ],
    ];
}

    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $searchModel->CompanyId=Yii::$app->user->identity->CompanyId;
        
		//$searchModel->EmployeeRoleId=[EmployeeRole::ADMIN,EmployeeRole::MANAGER,EmployeeRole::NORMAL_EMPLOYEEE];

        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
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
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Employee();
        $model->scenario = 'create';
        
        
        
        
        if ($model->load(Yii::$app->request->post())) {
            
            
            $oldempcontact=Employee::find()->where(['ContactNo'=>$model->ContactNo,'CompanyId'=>$model->CompanyId,'IsDelete' =>Employee::STATUS_NOT_DELETED])->one();
            $oldempemail=Employee::find()->where(['EmailId'=>$model->EmailId,'CompanyId'=>$model->CompanyId,'IsDelete' =>Employee::STATUS_NOT_DELETED])->one();
            
            if($oldempcontact){
            Yii::$app->session->setFlash('error',Yii::t('company', $model->ContactNo." Already Exist"));    
           
                   
                   return $this->render('create', [
                       'model' => $model,
                       'EmployeeRole' => ArrayHelper::map(EmployeeRole::find()->where(['IsDelete'=>0])->andWhere(['>=','EmployeeRoleId',Yii::$app->user->identity->EmployeeRoleId])->andWhere(['!=','EmployeeRoleId',EmployeeRole::SUPPER_ADMIN])->asArray()->all(), 'EmployeeRoleId', 'EmployeeRole')
                   ]);
            }
            else if($oldempemail){
            Yii::$app->session->setFlash('error',Yii::t('company', $model->EmailId." Already Exist"));    
           
                   
                   return $this->render('create', [
                       'model' => $model,
                       'EmployeeRole' => ArrayHelper::map(EmployeeRole::find()->where(['IsDelete'=>0])->andWhere(['>=','EmployeeRoleId',Yii::$app->user->identity->EmployeeRoleId])->andWhere(['!=','EmployeeRoleId',EmployeeRole::SUPPER_ADMIN])->asArray()->all(), 'EmployeeRoleId', 'EmployeeRole')
                   ]);
            }            
            else{
              $model->setPassword(Yii::$app->request->post()['Employee']['Password']);
                 
                     if($model->save()){
                        Yii::$app->session->setFlash('success', "Your message to display.");
                        $model->CreditBalance=$model->CreditLimit;
                        $model->CreditOnHold=0;    
                        $model->save();               
                        return $this->redirect(['view', 'id' => $model->EmployeeId]);
                    } else {
                 
                 
                    $error=[];
                    
                    foreach($model->getErrors() as $attribute => $message){
                       $errorMesage="";
                       $errorMesage.="<strong>".$model->getAttributeLabel($attribute)."</strong> :";
                       $errorMesage.=implode(". ",$message);
                       $error[]=$errorMesage;
                   }
                   Yii::$app->session->setFlash('error',Yii::t('company', implode("<br>",$error)));
                   
                   return $this->render('create', [
                       'model' => $model,
                       'EmployeeRole' => ArrayHelper::map(EmployeeRole::find()->where(['IsDelete'=>0])->andWhere(['>=','EmployeeRoleId',Yii::$app->user->identity->EmployeeRoleId])->andWhere(['!=','EmployeeRoleId',EmployeeRole::SUPPER_ADMIN])->asArray()->all(), 'EmployeeRoleId', 'EmployeeRole')
                   ]);
               }  
                
            }
            
                 
       
   } else {
     
    return $this->render('create', [
        'model' => $model,
        'EmployeeRole' => ArrayHelper::map(EmployeeRole::find()->where(['IsDelete'=>0])->andWhere(['>=','EmployeeRoleId',Yii::$app->user->identity->EmployeeRoleId])->andWhere(['!=','EmployeeRoleId',EmployeeRole::SUPPER_ADMIN])->asArray()->all(), 'EmployeeRoleId', 'EmployeeRole')
    ]);
}
}

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'updateEmployee';
        $password=$model->Password;
        
        if ($model->load(Yii::$app->request->post())) {
         
        
        $oldempcontact=Employee::find()->where(['ContactNo'=>$model->ContactNo,'CompanyId'=>$model->CompanyId,'IsDelete' =>Employee::STATUS_NOT_DELETED])->andWhere(['<>','EmployeeId', $model->EmployeeId])->one();
        $oldempemail=Employee::find()->where(['EmailId'=>$model->EmailId,'CompanyId'=>$model->CompanyId,'IsDelete' =>Employee::STATUS_NOT_DELETED])->andWhere(['<>','EmployeeId', $model->EmployeeId])->one();
        
        if($oldempcontact){
        Yii::$app->session->setFlash('error',Yii::t('company', $model->ContactNo." Already Exist"));    
       
               
               return $this->render('create', [
                   'model' => $model,
                   'EmployeeRole' => ArrayHelper::map(EmployeeRole::find()->where(['IsDelete'=>0])->andWhere(['>=','EmployeeRoleId',Yii::$app->user->identity->EmployeeRoleId])->andWhere(['!=','EmployeeRoleId',EmployeeRole::SUPPER_ADMIN])->asArray()->all(), 'EmployeeRoleId', 'EmployeeRole')
               ]);
        }
        else if($oldempemail){
        Yii::$app->session->setFlash('error',Yii::t('company', $model->EmailId." Already Exist"));    
       
               
               return $this->render('create', [
                   'model' => $model,
                   'EmployeeRole' => ArrayHelper::map(EmployeeRole::find()->where(['IsDelete'=>0])->andWhere(['>=','EmployeeRoleId',Yii::$app->user->identity->EmployeeRoleId])->andWhere(['!=','EmployeeRoleId',EmployeeRole::SUPPER_ADMIN])->asArray()->all(), 'EmployeeRoleId', 'EmployeeRole')
               ]);
        }   


        else {
        if(Yii::$app->request->post()['Employee']['Password']!=""){
            $model->setPassword(Yii::$app->request->post()['Employee']['Password']);
        }
        else{
            $model->Password=$password;
            $model->password_repeat=$password;
        }
        
        
        
        
        
        
        
        if(Yii::$app->request->post()['Employee']['EmployeeRoleId']==EmployeeRole::NORMAL_EMPLOYEEE){
            $model->Password='';
            $model->password_repeat='';
        }
        
        
        if($model->save()){
            
            Yii::$app->session->setFlash('success', "Your message to display.");
            return $this->redirect(['view', 'id' => $model->EmployeeId]);
            
        } else {
         
         
            $error=[];
            
            foreach($model->getErrors() as $attribute => $message){
               $errorMesage="";
               $errorMesage.="<strong>".$model->getAttributeLabel($attribute)."</strong> :";
               $errorMesage.=implode(". ",$message);
               $error[]=$errorMesage;
           }
           
           Yii::$app->session->setFlash('error',Yii::t('company', implode("<br>",$error)));
           return $this->render('update', [
               'model' => $model,
               'EmployeeRole' => ArrayHelper::map(EmployeeRole::find()->where(['IsDelete'=>0])->andWhere(['>=','EmployeeRoleId',Yii::$app->user->identity->EmployeeRoleId])->andWhere(['!=','EmployeeRoleId',EmployeeRole::SUPPER_ADMIN])->asArray()->all(), 'EmployeeRoleId', 'EmployeeRole')
           ]);
       }
       
        }
   } else {
    return $this->render('update', [
        'model' => $model,
        'EmployeeRole' => ArrayHelper::map(EmployeeRole::find()->where(['IsDelete'=>0])->andWhere(['>=','EmployeeRoleId',Yii::$app->user->identity->EmployeeRoleId])->andWhere(['!=','EmployeeRoleId',EmployeeRole::SUPPER_ADMIN])->asArray()->all(), 'EmployeeRoleId', 'EmployeeRole')
    ]);
}
}

    /**
     * Deletes an existing Employee model.
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
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
