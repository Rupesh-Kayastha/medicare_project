<?php

namespace company\controllers;

use Yii;
use common\models\Orders;
use company\models\search\OrdersSearch;
use company\models\search\OrdersDirectCompanySearch;
use common\models\Employee;
use company\models\search\EmployeeSearch;
use common\models\EmployeeRole;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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

   	public function actionSelfOpenOrder()
    {
		
        $searchModel = new OrdersSearch();
		$searchModel->OrderStatus = 0;
		$searchModel->CompanyId=Yii::$app->user->identity->CompanyId;
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('selfopenorder', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }
	public function actionSelfConfirmOrder()
    {
        $searchModel = new OrdersSearch();
		$searchModel->OrderStatus = 1;
		$searchModel->CompanyId=Yii::$app->user->identity->CompanyId;
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('selfconfirmorder', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }
	public function actionSelfRejectOrder()
    {
        $searchModel = new OrdersSearch();
		$searchModel->OrderStatus = 2;
		$searchModel->CompanyId=Yii::$app->user->identity->CompanyId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('selfrejectorder', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }
    public function actionView($id,$ref)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
			'ref'=>$ref
        ]);
    }
    public function actionCompanyOpenOrder()
    {
		
        $searchModel = new OrdersDirectCompanySearch();
		$searchModel->OrderStatus = 0;
		$searchModel->CompanyId=Yii::$app->user->identity->CompanyId;
	
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('companyopenorder', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }
	public function actionCompanyConfirmOrder()
    {
        $searchModel = new OrdersDirectCompanySearch();
		$searchModel->OrderStatus = 1;
		$searchModel->CompanyId=Yii::$app->user->identity->CompanyId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('companyconfirmorder', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }
	public function actionCompanyRejectOrder()
    {
        $searchModel = new OrdersDirectCompanySearch();
		$searchModel->OrderStatus = 2;
		$searchModel->CompanyId=Yii::$app->user->identity->CompanyId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('companyrejectorder', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }
    

   
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
