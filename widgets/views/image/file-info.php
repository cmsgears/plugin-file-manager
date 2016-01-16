<?php

	if( $info ) {

		if( isset( $model ) ) {

			if( $seoInfoOnly ) { 
?>
				<div class='fields'>
					<label>Alternate Text</label> <input type='text' name='<?= $modelClass ?>[altText]' value='<?= $model->altText ?>' />
				</div>
<?php 
			}
			else {
?>
				<div class='fields'>
					<label>Title</label> <input type='text' name='<?= $modelClass ?>[title]' value='<?= $model->title ?>' />
					<label>Description</label> <input type='text' name='<?= $modelClass ?>[description]' value='<?= $model->description ?>' />
					<label>Alternate Text</label> <input type='text' name='<?= $modelClass ?>[altText]' value='<?= $model->altText ?>' />
					<label>Link</label> <input type='text' name='<?= $modelClass ?>[link]' value='<?= $model->link ?>' />
				</div>
<?php 
			}
		}
		else {

			if( $seoInfoOnly ) { 
?>
				<div class='fields'>
					<label>Alternate Text</label> <input type='text' name='<?= $modelClass ?>[altText]' />
				</div>
<?php 
			}
			else {
?>
				<div class='fields'>
					<label>Title</label> <input type='text' name='<?= $modelClass ?>[title]' />
					<label>Description</label> <input type='text' name='<?= $modelClass ?>[description]' />
					<label>Alternate Text</label> <input type='text' name='<?= $modelClass ?>[altText]' />
					<label>Link</label> <input type='text' name='<?= $modelClass ?>[link]' />
				</div>
<?php 
			}
		}
	}
?>