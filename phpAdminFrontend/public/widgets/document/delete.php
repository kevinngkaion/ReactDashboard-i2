<?php
$result = $ots->execute('document','delete',$args);
?>
<script>
	$(document).ready(function(){
		$(".notification-success-message").html("Record deleted.");		
		$(".notification-success").fadeIn('slow');
		window.location.href = "<?=WEB_ROOT;?>/document/";
	});
</script>