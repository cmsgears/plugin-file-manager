<?php
	if( $info ) {

		if( isset( $model ) ) {
?>
			<div class='fields'>
				<?php if( in_array( 'title', $infoFields ) ) { ?>
					<?php if( $infoLabel ) { ?> <label>Title</label> <?php } ?>
					<input type='text' name='<?= $modelClass ?>[title]' placeholder="Title" value='<?= $model->title ?>' />
				<?php } ?>

				<?php if( in_array( 'description', $infoFields ) ) { ?>
					<?php if( $infoLabel ) { ?> <label>Description</label> <?php } ?>
					<input type='text' name='<?= $modelClass ?>[description]' placeholder="Description" value='<?= $model->description ?>' />
				<?php } ?>

				<?php if( in_array( 'alt', $infoFields ) ) { ?>
					<?php if( $infoLabel ) { ?> <label>Alternate Text</label> <?php } ?>
					<input type='text' name='<?= $modelClass ?>[altText]' placeholder="Alternate Text" value='<?= $model->altText ?>' />
				<?php } ?>

				<?php if( in_array( 'link', $infoFields ) ) { ?>
					<?php if( $infoLabel ) { ?> <label>Link</label> <?php } ?>
					<input type='text' name='<?= $modelClass ?>[link]' placeholder="Link" value='<?= $model->link ?>' />
				<?php } ?>
			</div>
<?php
		}
		else {
?>
			<div class='fields'>
				<?php if( in_array( 'title', $infoFields ) ) { ?>
					<?php if( $infoLabel ) { ?> <label>Title</label> <?php } ?>
					<input type='text' name='<?= $modelClass ?>[title]' placeholder="Title" />
				<?php } ?>

				<?php if( in_array( 'description', $infoFields ) ) { ?>
					<?php if( $infoLabel ) { ?> <label>Description</label> <?php } ?>
					<input type='text' name='<?= $modelClass ?>[description]' placeholder="Description" />
				<?php } ?>

				<?php if( in_array( 'alt', $infoFields ) ) { ?>
					<?php if( $infoLabel ) { ?> <label>Alternate Text</label> <?php } ?>
					<input type='text' name='<?= $modelClass ?>[altText]' placeholder="Alternate Text" />
				<?php } ?>

				<?php if( in_array( 'link', $infoFields ) ) { ?>
					<?php if( $infoLabel ) { ?> <label>Link</label> <?php } ?>
					<input type='text' name='<?= $modelClass ?>[link]' placeholder="Link" />
				<?php } ?>
			</div>
<?php
		}
	}
?>