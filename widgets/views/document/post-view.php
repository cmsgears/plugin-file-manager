<?php
	if( $postView ) {

		if( isset( $model ) ) {

			$name	= $model->name;

			if( isset( $name ) ) {
					
				$url 	= $model->getFileUrl();
?>
				<div class='postview'>
					<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update File'></div>
					<div class='wrap-image'><video src='<?= $url ?>' controls class='fluid'>Video not supported.</video></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
				else {
?>
					<div class='postview'>
						<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update File'></div>
						<div class='wrap-image'><span class='<?= $postViewIcon ?>'></span></div>
						<div class='message-upload'><?= $postUploadMessage ?></div>
					</div>
<?php
				}
			}
			else {
?>
				<div class='postview'>
					<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update File'></div>
					<div class='wrap-image'><span class='<?= $postViewIcon ?>'></span></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
	}
?>