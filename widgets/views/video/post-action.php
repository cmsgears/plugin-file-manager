<?php 
if( $postAction && isset( $postActionUrl ) ) {

	$paClass = 'post-action';
	
	if( $postActionVisible ) {
	
		$paClass = 'post-action-v';
	}
?>
	<form></form>
	<div class='<?= $paClass ?>'>
		<form id='<?= $postActionId ?>' class='cmt-form' cmt-controller='<?= $cmtController ?>' cmt-action='<?= $cmtAction ?>' action='<?= $postActionUrl ?>' method='post'>
			<div class="max-area-cover spinner">
				<div class="valign-center cmti cmti-2x cmti-spinner-1 spin"></div>
			</div>
			<?= $attributesHtml ?>
			<?= $infoHtml ?>
			<div class="frm-actions align align-center">
				<input class="element-medium" type="submit" value="Save" />	
			</div>
		</form>
	</div>
<?php } else { ?>
	<?= $attributesHtml ?>
	<?= $infoHtml ?>
<?php } ?>