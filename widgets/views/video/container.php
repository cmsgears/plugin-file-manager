<?php
$model = $widget->model;

if( isset( $model ) && isset( $model->name ) && strlen( $model->name ) > 0 ) {

	$name	= $model->name;
	$url	= $model->getFileUrl();
?>
	<div class="card card-file">
		<div class="card-data file-data">
			<video src='<?= $url ?>' controls class='fluid'>Video not supported.</video>
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
