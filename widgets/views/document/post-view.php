<?php
	if( $postView ) {

		if( isset( $model ) ) {

			$name	= $model->name;

			if( isset( $name ) ) {

				$url 	= $model->getFileUrl();
?>
				<div class='postview'>
					<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update File'></div>
					<div class='wrap-file'><a href="<?= $url ?>" class='<?= $postViewIcon ?>' target="_blank"></a></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
			else {
?>
				<div class='postview'>
					<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update File'></div>
					<div class='wrap-file'><span class='<?= $postViewIcon ?>'></span></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
		}
		else {
?>
			<div class='postview'>
				<div class='btn-show-chooser <?= $btnChooserIcon ?>' title='Update File'></div>
				<div class='wrap-file'><span class='<?= $postViewIcon ?>'></span></div>
				<div class='message-upload'><?= $postUploadMessage ?></div>
			</div>
<?php
		}
	}
?>