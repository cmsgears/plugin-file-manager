<?php
$model		= $widget->model;
$modelClass	= $widget->modelClass;

if( isset( $model ) ) {
?>
	<div class="file-info">
		<input type="hidden" name="<?= $modelClass ?>[id]" class="id" value="<?= $model->id ?>" />
		<input type="hidden" name="<?= $modelClass ?>[name]" class="name" value="<?= $model->name ?>" />
		<input type="hidden" name="<?= $modelClass ?>[extension]" class="extension" value="<?= $model->extension ?>" />
		<input type="hidden" name="<?= $modelClass ?>[directory]" value="<?= $widget->directory ?>" />
		<input type="hidden" name="<?= $modelClass ?>[type]" value="<?= $widget->type ?>" />
		<input type="hidden" name="<?= $modelClass ?>[changed]" class="change" value="<?= $model->changed ?>" />
	</div>
<?php
}
else {
?>
	<div class="file-info">
		<input type="hidden" name="<?= $modelClass ?>[name]" class="name" />
		<input type="hidden" name="<?= $modelClass ?>[extension]" class="extension" />
		<input type="hidden" name="<?= $modelClass ?>[directory]" value="<?= $widget->directory ?>" />
		<input type="hidden" name="<?= $modelClass ?>[type]" value="<?= $widget->type ?>" />
		<input type="hidden" name="<?= $modelClass ?>[changed]" class="change" />
	</div>
<?php } ?>
