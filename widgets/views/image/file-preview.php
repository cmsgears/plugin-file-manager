<?php if( $preview && !$disabled ) { ?>

	<div class='preview'>
		<div class='wrap-drag'>
			<div class='drag'>Drag here</div>
			<canvas class='canvas' width='<?= $previewWidth ?>' height='<?= $previewHeight ?>' ></canvas>
		</div>
	</div>

<?php } ?>