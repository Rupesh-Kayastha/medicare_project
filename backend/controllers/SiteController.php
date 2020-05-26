<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use common\models\TicketOrder;
use common\models\ContactForm;
use common\models\TicketChat;
use backend\models\User;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','tokenautoclose','contactlist','bestseller'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','bestseller'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
		if(Yii::$app->user->identity)
        {
            $this->layout='main';
        }
        else
        {
            $this->layout='login';
        }
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $allticket=array();
        if(Yii::$app->user->identity->SystemRoleId==User::SYSTEM_USER_OPERATOR)
        {
			$allticket=TicketOrder::find()->where(['TicketStatus'=>1])->andWhere(['IN','Operator',[0,Yii::$app->user->identity->id]])->orderBy(['OnDate'=>SORT_DESC])->all();
        }
		
        return $this->render('index',['allticket'=>$allticket]);
    }
     public function actionContactlist()
    {
        $contactdetails=ContactForm::find()->where(['IsDelete'=>0])->orderBy(['OnDate'=>SORT_DESC])->all();
        return $this->render('contactlist',['contactdetails'=>$contactdetails]);
    }
	
    

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	
	public function actionTokenautoclose(){
		$allticketorder = TicketOrder::find()->where(['TicketStatus'=>1,'IsDelete'=>0])->all();
		
		foreach($allticketorder as $key => $value){
			$ticketChats = $value->lastTicketChats;
			if($ticketChats){
				$currentdatetime = date("Y-m-d h:i:s");
				$lastmessagetime = date("Y-m-d h:i:s",strtotime($ticketChats->OnDate));
				$second_difference = strtotime($currentdatetime) - strtotime($lastmessagetime);
				//600 -> 10 Minutes
				if($second_difference > 600){
					$ticketDetails = TicketOrder::find()->where(['Token'=>$value->Token,'IsDelete'=>0])->one();
					if($ticketDetails){
						$ticketDetails->TicketStatus = 2;
						if($ticketDetails->save()){
						  echo 1;
						}else{
							echo 0;
						}
					}
				}
			}else{
				
				$currentdatetime = date("Y-m-d h:i:s");
				$lastmessagetime = date("Y-m-d h:i:s",strtotime($value->OnDate));
				$second_difference = strtotime($currentdatetime) - strtotime($lastmessagetime);
				//600 -> 10 Minutes
				if($second_difference > 600){
					$ticketDetails = TicketOrder::find()->where(['Token'=>$value->Token,'IsDelete'=>0])->one();
					$ticketDetails->TicketStatus = 2;
					if($ticketDetails->save()){
						echo 1;
					}else{
						echo 0;
					}
				}
			}
		}
		
		
	}
	
	
	
}
?>