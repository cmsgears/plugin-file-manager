<?php
	if( $postView ) {

        $btnShowChooser = "<div class='btn-show-chooser $btnChooserIcon' title='Update File'></div>";

        if( $disabled ) {

            //$btnShowChooser = "<div class='btn-show-chooser' title='Update File disable'></div>";
            $btnShowChooser = "";
        }

		if( isset( $model ) ) {

			$name	= $model->name;

			if( isset( $name ) && strlen( $name ) > 0 ) {

				$url 	= $model->getFileUrl();
?>
				<div class='postview'>
					<?= $btnShowChooser ?>
					<div class='wrap-file'><a href="<?= $url ?>" class='<?= $postViewIcon ?>' target="_blank"></a></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
			else {
?>
				<div class='postview'>
					<?= $btnShowChooser ?>
					<div class='wrap-file'><span class='<?= $postViewIcon ?>'></span></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
		}
		else {
?>
			<div class='postview'>
				<?= $btnShowChooser ?>
				<div class='wrap-file'><span class='<?= $postViewIcon ?>'></span></div>
				<div class='message-upload'><?= $postUploadMessage ?></div>
			</div>
<?php
		}
	}
?>