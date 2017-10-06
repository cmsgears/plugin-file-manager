<?php if( $widget->dragger && !$widget->disabled ) { ?>
	<div class="card card-file-dragger file-dragger">
		<div class="card-data">
			<div class="drag-wrap">
				<div class="drag">Drag here</div>
				<canvas class="canvas" width="<?= $widget->previewWidth ?>" height="<?= $widget->previewHeight ?>" ></canvas>
			</div>
		</div>
	</div>
<?php } ?>
