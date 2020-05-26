<?php

namespace company\controllers;

use Yii;
use common\models\EmiSchedules;
use company\models\search\EmiSchedulesSearch;
use common\models\Employee;
use company\models\search\EmployeeSearch;
use common\models\EmployeeRole;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * EmiSchedulesController implements the CRUD actions for EmiSchedules model.
 */
class EmiSchedulesController extends Controller
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
     * Lists all EmiSchedules models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmiSchedulesSearch();
		$searchModel->CompanyId=Yii::$app->user->identity->CompanyId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddemi()
    {
        if(Yii::$app->request->post())
        {
            $id=Yii::$app->request->post('id');
            $eid=Yii::$app->request->post('eid');
            $model = $this->findModel($id);
            $Emi= Employee::find()->where(['EmployeeId'=>$eid])->one();
            $Balance=$Emi->CreditBalance;
            if($model->EmiClearingStatus == 0)
            {
            $model->EmiClearingStatus=1;
            if ($model->save()) {
                $Emi->CreditBalance=$Balance+Yii::$app->request->post('EmiAmount');
                if ($Emi->save()) {
                    Yii::$app->session->setFlash('success', "Added successfully.");
                    return $this->redirect(['index']);
                }
                else {Yii::$app->session->setFlash('error', "There is some Error.");  }
            }
        }
        else{Yii::$app->session->setFlash('success', "Already Confirmed.");}
    }
    return $this->redirect(['index']);
    }

   
    /**
     * Finds the EmiSchedules model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmiSchedules the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmiSchedules::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
