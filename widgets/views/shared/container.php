<?php
$model = $widget->model;

if( isset( $model ) && isset( $model->name ) && strlen( $model->name ) > 0 ) {

	$name	= $model->name;
	$url	= $model->getFileUrl();
?>
	<div class="card card-file">
		<div class="card-data file-data">
			<?php
				switch( $model->type ) {

					case 'image': {
			?>
						<img src="<?= $url ?>" class="fluid" />
			<?php
						break;
					}
					case 'video': {
			?>
						<video src='<?= $url ?>' controls class='fluid'>Video not supported.</video>
			<?php
						break;
					}
					case 'compressed': {
			?>
						<a href="<?= $url ?>" class="icon cmti cmti-5x cmti-file-archive" target="_blank"></a>
			<?php
						break;
					}
					default: {
			?>
						<a href="<?= $url ?>" class="<?= $widget->fileIcon ?>" target="_blank"></a>
			<?php
					}
				}
			?>
		</div>
		<div class="card-footer message-upload">
			<?= $widget->uploadMessage ?>
		</div>
	</div>
<?php
}
else {
?>
	<div class="card card-file">
		<div class="card-data file-data">
			<i class="<?= $widget->fileIcon ?>"></i>
		</div>
		<div class="card-footer message-upload">
			<?= $widget->uploadMessage ?>
		</div>
	</div>
<?php
}
?>
