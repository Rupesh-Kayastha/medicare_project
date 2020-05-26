<?php
use backend\models\User;
/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
<div class="row">
	
	<?php 
	Yii::$app->user->identity->SystemRoleId; if(Yii::$app->user->identity->SystemRoleId==User::SYSTEM_USER_OPERATOR) :
    echo Yii::$app->controller->renderPartial('_chat',['allticket'=>$allticket]);
	endif;
	?>
</div>

