<?php
$postAction 		= $widget->postAction;
$postActionUrl		= $widget->postActionUrl;
$postActionVisible	= $widget->postActionVisible;
$cmtApp				= $widget->cmtApp;
$cmtController		= $widget->cmtController;
$cmtAction			= $widget->cmtAction;

if( $postAction && isset( $postActionUrl ) ) {

	$paClass = 'post-action';

	if( $postActionVisible ) {

		$paClass = 'post-action-v';
	}
?>
	<form></form>
	<div class="<?= $paClass ?>">
		<form cmt-app="<?= $cmtApp ?>" cmt-controller="<?= $cmtController ?>" cmt-action="<?= $cmtAction ?>" action="<?= $postActionUrl ?>" method='post'>
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