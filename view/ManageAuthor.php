<?php
	session_start();
	if(!isset($_SESSION['userAuthority'])||$_SESSION['userAuthority']=='1') echo "<script>window.location='http://localhost'</script>";
	require_once '../control/conn.php';
?>
	<Button type="button" class="pull-right btn btn-primary glyphicon glyphicon-refresh" onclick="changeView('ManageAuthor')"></Button>
	<div class="starter-template bs-div pull-left" style="width:60%;" id="authorList">
		<?php include '../view/authorList.php';?>
	</div>
	<div class="starter-template pull-right" style="width:40%;" id="authorInsert">
		<?php include '../view/authorInsert.php'; ?>
	</div>