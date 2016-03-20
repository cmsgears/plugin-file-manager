<?php

	if( $postView ) {

        $btnShowChooser = "<div class='btn-show-chooser $btnChooserIcon' title='Update Image'></div>";
        
        if( $disabled ) {
            
            $btnShowChooser = "<div class='btn-show-chooser' title='Update Image disable'></div>";
        }
                
		if( isset( $model ) ) {

			$name	= $model->name;

			if( isset( $name ) ) {
					
				$url 	= $model->getThumbUrl();
?>
				<div class='postview'>
					<?=$btnShowChooser?>
					<div class='wrap-file'><img src='<?= $url ?>' class='fluid' /></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
			else {
?>
				<div class='postview'>
					<?=$btnShowChooser?>
					<div class='wrap-file'><span class='<?= $postViewIcon ?>'></span></div>
					<div class='message-upload'><?= $postUploadMessage ?></div>
				</div>
<?php
			}
		}
		else {
?>
			<div class='postview'>
				<?=$btnShowChooser?>
				<div class='wrap-file'><span class='<?= $postViewIcon ?>'></span></div>
				<div class='message-upload'><?= $postUploadMessage ?></div>
			</div>
<?php
		}
	}
?>