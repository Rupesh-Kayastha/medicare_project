<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	 <link rel="icon" href="../images/favicon.png" type="image/png" > 
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $this->render('_header.php') ?>

<?= $this->render('normal_topbar.php') ?>	
	
<?= $this->render('_content.php',['content' => $content]) ?>

<?= $this->render('_footer.php') ?>

<?php $this->endBody() ?>
</body>
<script type="text/javascript">
	function cartReview(cartidentifier){
		var url = "<?= Url::to(['site/viewoperatorcart']);?>?crtid="+cartidentifier;
		var win = window.open(url, '_blank');
		win.focus();
	}
</script>
</html>
<?php $this->endPage() ?>
