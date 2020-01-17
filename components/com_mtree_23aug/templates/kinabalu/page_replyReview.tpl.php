 
<div id="listing">
<h2><?php 
$link_name = $this->fields->getFieldById(1);
$this->plugin( 'ahreflisting', $this->link, $link_name->getOutput(1), '', array("edit"=>false,"delete"=>false) ) ?></h2>

<?php
$hide_title = true;
$hide_submitreview = true;
$hide_vote_helpful = true;
include $this->loadTemplate( 'sub_reviews.tpl.php' );
?>


<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.mtForm;
		form.task.value=pressbutton;
		if (pressbutton == 'cancel') {
			form.task.value='viewlink';
			form.submit();
			return;
		}
		try {
			form.onsubmit();
			}
		catch(e){}
		form.submit();
	}
</script>

<br clear="all" />
<div class="title"><?php echo JText::_( 'COM_MTREE_REPLY_REVIEW' ); ?></div>
<form action="<?php echo JRoute::_("index.php") ?>" method="post" name="mtForm" id="mtForm" class="form-horizontal">

	<?php if( $this->user_id <= 0 ) { ?>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_( 'COM_MTREE_YOUR_NAME' ) ?></label>
		<div class="controls">
			<input type="text" name="your_name" class="span8" size="40" />
		</div>
	</div>
	<?php } ?>

	<div class="control-group">
		<label class="control-label"><?php echo JText::_( 'COM_MTREE_MESSAGE' ) ?></label>
		<div class="controls">
		<textarea name="message" rows="8" cols="69" class="span8"></textarea>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<button type="button" onclick="javascript:submitbutton('send_replyreview')" class="btn btn-primary"><?php echo JText::_( 'COM_MTREE_SEND' ) ?></button>
			<button type="button" onclick="javascript:submitbutton('cancel')" class="btn"><?php echo JText::_( 'COM_MTREE_CANCEL' ) ?></button>
		</div>
	</div>

	<input type="hidden" name="option" value="<?php echo $this->option ?>" />
	<input type="hidden" name="task" value="send_replyreview" />
	<input type="hidden" name="rev_id" value="<?php echo $this->review->rev_id ?>" />
	<input type="hidden" name="link_id" value="<?php echo $this->review->link_id ?>" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid ?>" />
	<?php echo JHtml::_( 'form.token' ); ?>

</form>
</div>