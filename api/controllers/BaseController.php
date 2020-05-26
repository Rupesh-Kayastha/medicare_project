<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class BaseController extends Controller
{
    public function beforeAction($action)
    {            
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['POST'],
                ],
            ],
            'corsFilter' => [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                
                       'Origin' => ['*'],
                       'Access-Control-Request-Method' => ['POST', 'GET','PUT', 'OPTIONS','DELETE'],
                       'Access-Control-Request-Headers' => ['*'],
                    ],

            ],
        ];
    }
   
    public function sendResponce($data)
    {
        return $this->asJson($data);
    }
    
    
}
