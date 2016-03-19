<?php
	session_start();
	if(!isset($_SESSION['userAuthority'])) echo "<script>window.location='http://localhost'</script>";
	require_once '../control/conn.php';
?>
<div style="min-height:80%;margin-top:2%;">
	<div class="col-xs-3"> <!-- required for floating -->
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs tabs-left">
	    <li class="" id="li_info"><a href="#info" data-toggle="tab">Account Setting</a></li>
	    <li class="" id="li_log"><a href="#log" data-toggle="tab">Check In/Out Log</a></li>
	    <li class="" id="li_message"><a href="#message" data-toggle="tab">Message Box</a></li>
	   	<li class="" id="li_fine"><a href="#fine" data-toggle="tab">Fine List</a></li>
	  </ul>
	</div>

	<div class="col-xs-9">
	  <!-- Tab panes -->
	  <div class="tab-content">
	    <div class="tab-pane fade " id="info"></div>
	    <div class="tab-pane fade " id="log"></div>
	    <div class="tab-pane fade " id="message"></div>
	    <div class="tab-pane fade " id="fine"></div>
	  </div>
	</div>
</div>

<script type="text/javascript">
	var id="<?php echo $_GET['action'];?>";
	var changeDiv= function(_id){
		switch(_id){
			case 'info':
				url="memberUpdate.php";
			break;
			case 'log':
				url="memberLogList.php";
			break;
			case 'message':
				url="memberMessage.php";
			break;
			case 'fine':
				url="memberFine.php";
			break;
		}
		$.ajax({
			type:"POST",
			url:'../view/'+url,
			data:'',
			success:function(data){
				$("div#"+_id).html(data);
			}
		});
	}
	$(document).ready(function(){
		$("li,div.tab-pane").removeClass('in active');
		$("li#li_"+id+",div#"+id).addClass('in active');
		changeDiv(id);
	})
	$("li").click(function(){
		var _id=this.id.split('_')[1];
		changeDiv(_id);
	})
	
</script>
