<?php
	if( $postView ) {

		if( isset( $model ) ) {

			$name	= $model->name;

			if( isset( $name ) ) {
					
				$url 	= $model->getFileUrl();
?>
				<div class='postview'>
					<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update Video'></div>
					<div class='wrap-file'><video src='<?= $url ?>' controls class='fluid'>Video not supported.</video></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
			else {
?>
				<div class='postview'>
					<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update Video'></div>
					<div class='wrap-file'><span class='<?= $postViewIcon ?>'></span></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
		}
		else {
?>
			<div class='postview'>
				<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update Video'></div>
				<div class='wrap-file'><span class='<?= $postViewIcon ?>'></span></div>
				<div class='message-upload'><?= $postUploadMessage ?></div>
			</div>
<?php
		}
	}
?>