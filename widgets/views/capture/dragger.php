<?php if( $widget->dragger && !$widget->disabled ) { ?>
	<div class="card card-file-dragger file-camera">
		<div class="card-data">
			<div class="camera-wrap">
				<video class="video" width="<?= $widget->previewWidth ?>" height="<?= $widget->previewHeight ?>"></video>
				<canvas class="canvas hidden" width="<?= $widget->previewWidth ?>" height="<?= $widget->previewHeight ?>" data-name="capture-<?= $widget->parentId ?>.png"></canvas>
			</div>
		</div>
	</div>
<?php } ?>
