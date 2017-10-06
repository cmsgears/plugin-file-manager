<?php
$postAction 		= $widget->postAction;
$postActionUrl		= $widget->postActionUrl;
$postActionVisible	= $widget->postActionVisible;
$cmtApp				= $widget->cmtApp;
$cmtController		= $widget->cmtController;
$cmtAction			= $widget->cmtAction;

if( $postAction && isset( $postActionUrl ) ) {

	$paClass = 'post-action hidden-easy';

	if( $postActionVisible ) {

		$paClass = 'post-action';
	}
?>
	<div class="<?= $paClass ?> clearfix">
		<form cmt-app="<?= $cmtApp ?>" cmt-controller="<?= $cmtController ?>" cmt-action="<?= $cmtAction ?>" action="<?= $postActionUrl ?>">
			<div class="max-area-cover spinner">
				<div class="valign-center cmti cmti-2x cmti-spinner-1 spin"></div>
			</div>
			<?= $infoHtml ?>
			<?= $fieldsHtml ?>
			<div class="frm-actions align align-center">
				<input class="element-medium" type="submit" value="Save" />
			</div>
		</form>
	</div>
<?php } else { ?>
	<?= $infoHtml ?>
	<?= $fieldsHtml ?>
<?php } ?>
