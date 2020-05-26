<div id="site_content">
	
		
			<?= \yiister\gentelella\widgets\FlashAlert::widget(['showHeader' => false]) ?>
			<div class="clearfix"></div>
		<?php if(isset($this->params['categorySidebar']) && $this->params['categorySidebar']){ ?>
			
			<?= $this->render('_categorySidebar.php',["data"=>$this->params['data']]) ?>
			
		<?php }?>
		<?= $content ?>
		
	
</div>