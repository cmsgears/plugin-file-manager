<?php
	if( $postView ) {

        $btnShowChooser = "<div class='btn-show-chooser $btnChooserIcon' title='Update Video'></div>";

        if( $disabled ) {

            //$btnShowChooser = "<div class='btn-show-chooser' title='Update Video disable'></div>";
            $btnShowChooser = "";
        }

		if( isset( $model ) ) {

			$name	= $model->name;

			if( isset( $name ) && strlen( $name ) > 0 ) {

				$url 	= $model->getFileUrl();
?>
				<div class='postview'>
					<?= $btnShowChooser ?>
					<div class='wrap-file'><video src='<?= $url ?>' controls class='fluid'>Video not supported.</video></div>
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