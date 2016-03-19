<?php
	session_start();
	if(!isset($_SESSION['userAuthority'])) echo "<script>window.location='http://localhost'</script>";
	require_once '../control/conn.php';
?>
<div class="panel panel-danger" style="margin-top:2%;">
	<div class="panel-heading">
	    <h3 class="panel-title"><?php echo $_SESSION['userName']."'s Fine List";?></h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped" id="finelist">
		    <thead>
		        <tr>
		          <th><a>Book Name</a></th>
		          <th><a>Check In</a></th>
		          <th><a>Check Out</a></th>
		          <th><a>Due</a></th>
		          <th><a>Days</a></th>
		          <th><a>Fine</a></th>
		        </tr>
		    </thead>
		    <tbody>
			</tbody>
		</table>
    </div>
</div>
<script type="text/javascript">
	$.ajax({
		type:"POST",
		url:'../control/getFine.php',
		data:'account=<?php echo $_SESSION["userAccount"]?>',
		dataType:'json',
		success:function(data){
			console.log(data);
			if(data[0]){
				$("#finelist").find("tbody tr").remove();
				for(var key in data[0]){
					$("#finelist tbody").append('<tr class="warning"><td>'+data[0][key]['title']+'</td><td>'+data[0][key]['checkin']+'</td><td>'+data[0][key]['checkout']+'</td><td>'+data[0][key]['due']+'</td><td>'+data[0][key]['days']+'days</td><td>$'+data[0][key]['fine']+'</td></tr>');
				}
			}
			if(data[1]){
				$("#finelist tbody").append('<tr class="danger"><td colspan="4"></td><td><strong>Total</strong></td><td><span class="glyphicon glyphicon-usd"></span><strong>'+data[1]['total']+'</strong></td></tr>');

			}
		}
	});
	// $(document).ready(){
	// 	if(data[1]){
	// 		$("#overdue").find("tbody tr").remove();
	// 		$("#overdue").find('.panel-title').html('down is account :<strong>'+data[0]['account']+'</strong> fine list');
	// 		for(var key in data[1]){
	// 			$("#overdue tbody").append('<tr class="warning"><td>'+data[1][key]['title']+'</td><td>'+data[1][key]['checkin']+'</td><td>'+data[1][key]['checkout']+'</td><td>'+data[1][key]['due']+'</td><td>'+data[1][key]['days']+'days</td><td>$'+data[1][key]['fine']+'</td></tr>');
	// 		}
	// 	}
	// 	if(data[2]){
	// 		$("#overdue tbody").append('<tr class="danger"><td colspan="4"></td><td><strong>Total</strong></td><td><span class="glyphicon glyphicon-usd"></span><strong>'+data[2]['total']+'</strong></td></tr>');
	// 		$("#overdue").show();
	// 	}
	// }

</script>