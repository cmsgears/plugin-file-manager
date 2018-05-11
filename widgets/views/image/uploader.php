<?php if( !$widget->disabled ) { ?>
<span class="btn-icon btn-chooser" title="Update Image">
	<i class="<?= $widget->chooserIcon ?>"></i>
</span>
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
