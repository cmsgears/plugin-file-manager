<?php
	if( $postView ) {

		if( isset( $model ) ) {

			$name	= $model->name;

			if( isset( $name ) ) {
					
				$url 	= $model->getThumbUrl();
?>
				<div class='postview'>
					<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update Image'></div>
					<div class='wrap-image'><img src='<?= $url ?>' class='fluid' /></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
				else {
?>
					<div class='postview'>
						<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update Image'></div>
						<div class='wrap-image'><span class='<?= $postViewIcon ?>'></span></div>
						<div class='message-upload'><?= $postUploadMessage ?></div>
					</div>
<?php
				}
			}
			else {
?>
				<div class='postview'>
					<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update Image'></div>
					<div class='wrap-image'><span class='<?= $postViewIcon ?>'></span></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
	}
?>