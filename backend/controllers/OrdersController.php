<?php

namespace backend\controllers;

use Yii;
use common\models\Orders;
use common\models\search\OrdersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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
	public function actionOpenOrder()
    {
        $searchModel = new OrdersSearch();
		$searchModel->OrderStatus = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('openorder', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }
	public function actionConfirmOrder()
    {
        $searchModel = new OrdersSearch();
		$searchModel->OrderStatus = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('confirmorder', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
		
    }
	public function actionRejectOrder()
    {
        $searchModel = new OrdersSearch();
		$searchModel->OrderStatus = 2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('rejectorder', [
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
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionOrderConfirm(){
		
		$post = Yii::$app->request->post();
		$OrderId = $post['OrderId'];
		$order_inst = new Orders();
		$order_result = $order_inst->adminOrderapprove($OrderId);
		$json = array();
		$json['status'] = $order_result['status'];
		$json['msg'] = $order_result['msg'];
		return $this->asJson($json);		
		
	}
	public function actionOrderReject(){
		$post = Yii::$app->request->post();
		$OrderId = $post['OrderId'];
		$Comment = $post['Comment'];
		$order_inst = new Orders();
		$order_result = $order_inst->adminOrderreject($OrderId,$Comment);
		$json = array();
		$json['status'] = $order_result['status'];
		$json['msg'] = $order_result['msg'];
		return $this->asJson($json);
        	
	}
	
	
	/*
	//  Default action
	public function actionIndex()
    {
        $searchModel = new OrdersSearch();
		$searchModel->OrderStatus = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Your message to display.");
            return $this->redirect(['view', 'id' => $model->OrderId]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Your message to display.");
            return $this->redirect(['view', 'id' => $model->OrderId]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', "Your message to display.");
        return $this->redirect(['index']);
    }
	*/
}
