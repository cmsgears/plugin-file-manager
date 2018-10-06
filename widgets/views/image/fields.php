<?php
$model		= $widget->model;
$modelClass	= $widget->modelClass;
$fileLabel	= $widget->fileLabel;
$fileFields	= $widget->fileFields;
$embedLink	= $widget->showEmbedLink;

$postAction 	= $widget->postAction;
$postActionUrl	= $widget->postActionUrl;
$fieldClass		= $widget->showFields || ( $postAction && isset( $postActionUrl ) ) ? null : 'hidden-easy';

if( isset( $model ) ) {
?>
	<div class="file-fields form <?= $fieldClass ?>">
		<?php if( in_array( 'title', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Title</label> <?php } ?>
			<input class="title" type="text" name="<?= $modelClass ?>[title]" placeholder="Title" value="<?= $model->title ?>" />
		<?php } ?>

		<?php if( in_array( 'description', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Description</label> <?php } ?>
			<input class="desc" type="text" name="<?= $modelClass ?>[description]" placeholder="Description" value="<?= $model->description ?>" />
		<?php } ?>

		<?php if( in_array( 'alt', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Alternate Text</label> <?php } ?>
			<input class="alt" type="text" name="<?= $modelClass ?>[altText]" placeholder="Alternate Text" value="<?= $model->altText ?>" />
		<?php } ?>

		<?php if( in_array( 'link', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Link</label> <?php } ?>
			<input class="ref" type="text" name="<?= $modelClass ?>[link]" placeholder="Link" value="<?= $model->link ?>" />
		<?php } ?>

		<?php if( $embedLink ) { ?>
			<p class="bold align align-left margin margin-small-v">Embeddable Links</p>
			<div class="row">
				<label>Thumb Url</label>
				<input type="text" name="etlink" value="<?= $model->getThumbUrl() ?>" readonly />
			</div>
			<div class="row">
				<label>Medium Url</label>
				<input type="text" name="etlink" value="<?= $model->getMediumUrl() ?>" readonly />
			</div>
			<div class="row">
				<label>File Url</label>
				<input type="text" name="eflink" value="<?= $model->getFileUrl() ?>" readonly />
			</div>
		<?php } ?>
	</div>
<?php
}
else {
?>
	<div class="file-fields form <?= $fieldClass ?>">
		<?php if( in_array( 'title', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Title</label> <?php } ?>
			<input class="title" type="text" name="<?= $modelClass ?>[title]" placeholder="Title" />
		<?php } ?>

		<?php if( in_array( 'description', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Description</label> <?php } ?>
			<input class="desc" type="text" name="<?= $modelClass ?>[description]" placeholder="Description" />
		<?php } ?>

		<?php if( in_array( 'alt', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Alternate Text</label> <?php } ?>
			<input class="alt" type="text" name="<?= $modelClass ?>[altText]" placeholder="Alternate Text" />
		<?php } ?>

		<?php if( in_array( 'link', $fileFields ) ) { ?>
			<?php if( $fileLabel ) { ?> <label>Link</label> <?php } ?>
			<input class="ref" type="text" name="<?= $modelClass ?>[link]" placeholder="Link" />
		<?php } ?>
	</div>
<?php
}
?>
