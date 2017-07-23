<?php
$model		= $widget->model;
$modelClass	= $widget->modelClass;
$fileLabel	= $widget->fileLabel;
$fileFields	= $widget->fileFields;

if( isset( $model ) ) {
?>
	<div class="file-fields">
		<?php if( in_array( 'title', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Title</label> <?php } ?>
			<input type="text" name="<?= $modelClass ?>[title]" placeholder="Title" value="<?= $model->title ?>" />
		<?php } ?>

		<?php if( in_array( 'description', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Description</label> <?php } ?>
			<input type="text" name="<?= $modelClass ?>[description]" placeholder="Description" value="<?= $model->description ?>" />
		<?php } ?>

		<?php if( in_array( 'alt', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Alternate Text</label> <?php } ?>
			<input type="text" name="<?= $modelClass ?>[altText]" placeholder="Alternate Text" value="<?= $model->altText ?>" />
		<?php } ?>

		<?php if( in_array( 'link', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Link</label> <?php } ?>
			<input type="text" name="<?= $modelClass ?>[link]" placeholder="Link" value="<?= $model->link ?>" />
		<?php } ?>
	</div>
<?php
}
else {
?>
	<div class="file-fields">
		<?php if( in_array( 'title', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Title</label> <?php } ?>
			<input type="text" name="<?= $modelClass ?>[title]" placeholder="Title" />
		<?php } ?>

		<?php if( in_array( 'description', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Description</label> <?php } ?>
			<input type="text" name="<?= $modelClass ?>[description]" placeholder="Description" />
		<?php } ?>

		<?php if( in_array( 'alt', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Alternate Text</label> <?php } ?>
			<input type="text" name="<?= $modelClass ?>[altText]" placeholder="Alternate Text" />
		<?php } ?>

		<?php if( in_array( 'link', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Link</label> <?php } ?>
			<input type="text" name="<?= $modelClass ?>[link]" placeholder="Link" />
		<?php } ?>
	</div>
<?php
}
?>
