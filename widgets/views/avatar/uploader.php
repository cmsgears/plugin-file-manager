<?php
$model = $widget->model;
?>
<?php if( !$widget->disabled ) { ?>
<div class="uploader-actions">
	<span class="uploader-action btn-icon btn-chooser" title="Update Avatar">
		<i class="<?= $widget->chooserIcon ?>"></i>
	</span>
	<?php if( $widget->clearAction && $widget->clearActionVisible ) { ?>
		<span class="<?= empty( $model ) || empty( $model->id ) ? 'file-clear hidden-easy' : 'file-clear' ?>" cmt-app="<?= $widget->cmtApp ?>" cmt-controller="<?= $widget->cmtController ?>" cmt-action="<?= $widget->cmtClearAction ?>" action="<?= $widget->clearActionUrl ?>">
			<span class="uploader-action btn-icon btn-clear cmt-click" title="Clear Avatar">
				<i class="<?= $widget->clearIcon ?>"></i>
			</span>
		</span>
	<?php } ?>
</div>
<?php } ?>

<div class="box-content">
	<div class="box-content-data">
		<div class="file-wrap">
			<?= $containerHtml ?>
		</div>
		<div class="chooser-wrap">
			<?= $draggerHtml ?>

			<?php if( $widget->chooser && $widget->dragger ) { ?>
				<div class="filler-height"></div>
				<div class="text-with-line row row-medium"><span class="text-content bold">OR</span></div>
				<div class="filler-height"></div>
			<?php } ?>

			<?= $chooserHtml ?>

			<?= $preloaderHtml ?>
		</div>
		<div class="form-wrap">
			<?= $formHtml ?>
		</div>
	</div>
</div>
