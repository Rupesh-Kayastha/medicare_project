<?php
use yii\helpers\Url;
?>
<div id="live-chat">
		
		<header class="clearfix">
			
			<a href="#" class="chat-close">x</a>
				
			<h4><?=Yii::$app->user->identity?Yii::$app->user->identity->EmployeeName:'';?></h4>

			<span class="chat-message-counter">0</span>

		</header>

		<div class="chat">
			
			<div class="chat-history">
			</div> <!-- end chat-history -->

			<!--<p class="chat-feedback">Your partner is typing…</p>-->

			<form action="#/" method="get">

				<fieldset>
					
					<input type="text" placeholder="Type your message…" id="chat_input" autofocus autocomplete="off">
					<input type="hidden">

				</fieldset>

			</form>

		</div> <!-- end chat -->

	</div> <!-- end live-chat -->
  
	<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog" style="overflow: initial !important;">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">Prescription Preview</h4>
		  </div>
		  <div class="modal-body" style="max-height: calc(100vh - 200px); overflow: auto;">
			<center>
			<img src="" id="imagepreview" style="width:90%;padding:5%;">
			</center>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	
<script type="text/javascript">
	
	function viewPresscription(ele){
		$('#imagepreview').attr('src', $(ele).data('src'));
		$('#imagemodal').modal('show');
	}
</script>