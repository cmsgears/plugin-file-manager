<div class='fields'>
	<?php if( isset( $model ) ) { ?>
		<input type='hidden' name='<?= $modelClass ?>[id]' value='<?= $model->id ?>' />
		<input type='hidden' name='<?= $modelClass ?>[name]' class='name' value='<?= $model->name ?>' />
		<input type='hidden' name='<?= $modelClass ?>[extension]' class='extension' value='<?= $model->extension ?>' />
		<input type='hidden' name='<?= $modelClass ?>[directory]' value='<?= $directory ?>' />
		<input type='hidden' name='<?= $modelClass ?>[type]' value='<?= $type ?>' />
		<input type='hidden' name='<?= $modelClass ?>[changed]' class='change' value='<?= $model->changed ?>' />
	<?php } else { ?>
		<input type='hidden' name='<?= $modelClass ?>[name]' class='name' />
		<input type='hidden' name='<?= $modelClass ?>[extension]' class='extension' />
		<input type='hidden' name='<?= $modelClass ?>[directory]' value='<?= $directory ?>' />
		<input type='hidden' name='<?= $modelClass ?>[type]' value='<?= $type ?>' />
		<input type='hidden' name='<?= $modelClass ?>[changed]' class='change' />
	<?php } ?>

	<?php 
		if( $hiddenInfo ) {

			foreach ( $hiddenInfoFields as $key => $value ) {
	?>
				<input type='hidden' name='<?= $modelClass ?>[<?= $key ?>]' value='<?= $value ?>' />
	<?php
			}
		} 
	?>
</div>