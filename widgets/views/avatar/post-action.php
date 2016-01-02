<?php 
use yii\helpers\Url;

	if( $postAction && isset( $postActionUrl ) ) {

			$paClass = 'post-action';

			if( $postActionVisible ) {

				$paClass = 'post-action-v';
			}

			$postActionUrl	= Url::toRoute( [ $postActionUrl ], true );
?>

	<div class='<?= $paClass ?>'>
		<form id='<?= $postActionId ?>' class='cmt-form' cmt-controller='<?= $cmtController ?>' cmt-action='<?= $cmtAction ?>' action='<?= $postActionUrl ?>' method='post'>
			<?= $fieldsHtml ?>
			<?= $infoFieldsHtml ?>
			<input type='submit' value='Save' /> 
		</form>
	</div>

<?php } else { ?>

			<?= $fieldsHtml ?>
			<?= $infoFieldsHtml ?>
<?php } ?>