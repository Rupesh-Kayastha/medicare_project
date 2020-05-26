<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Company;

class CompanyController extends BaseController
{
 
    public function actionAllcompany()
    {
        $data=array();
        $results=array();
        $rescompany=Company::find()->where(['ActiveStatus'=>1,'IsDelete'=>0])->all();
        if(count($rescompany)>0)
        {
            foreach($rescompany as $key=>$value)
            {
                $data[$key]['CompanyId']=$value->CompanyId;
                $data[$key]['CompanyName']=$value->Name;
            }
            
            $status=1;
            $msg='Success';
        }
        else
        {
            $status=0;
            $msg='Fail';
        }
        
        $results['status']=$status;
        $results['message']=$msg;
        $results['data']=$data;
        
        $this->sendResponce($results);
    }
    
}
?>