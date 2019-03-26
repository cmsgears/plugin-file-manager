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
		<input type="hidden" name="<?= $modelClass ?>[width]" value="<?= $width ?>" />
		<input type="hidden" name="<?= $modelClass ?>[height]" value="<?= $height ?>" />
		<input type="hidden" name="<?= $modelClass ?>[mwidth]" value="<?= $mwidth ?>" />
		<input type="hidden" name="<?= $modelClass ?>[mheight]" value="<?= $mheight ?>" />
		<input type="hidden" name="<?= $modelClass ?>[swidth]" value="<?= $swidth ?>" />
		<input type="hidden" name="<?= $modelClass ?>[sheight]" value="<?= $sheight ?>" />
		<input type="hidden" name="<?= $modelClass ?>[twidth]" value="<?= $twidth ?>" />
		<input type="hidden" name="<?= $modelClass ?>[theight]" value="<?= $theight ?>" />
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
		<input type="hidden" name="<?= $modelClass ?>[width]" value="<?= $width ?>" />
		<input type="hidden" name="<?= $modelClass ?>[height]" value="<?= $height ?>" />
		<input type="hidden" name="<?= $modelClass ?>[mwidth]" value="<?= $mwidth ?>" />
		<input type="hidden" name="<?= $modelClass ?>[mheight]" value="<?= $mheight ?>" />
		<input type="hidden" name="<?= $modelClass ?>[swidth]" value="<?= $swidth ?>" />
		<input type="hidden" name="<?= $modelClass ?>[sheight]" value="<?= $sheight ?>" />
		<input type="hidden" name="<?= $modelClass ?>[twidth]" value="<?= $twidth ?>" />
		<input type="hidden" name="<?= $modelClass ?>[theight]" value="<?= $theight ?>" />
	</div>
<?php } ?>
