<?php
	require_once '../control/conn.php';
?>
<div class="col-xs-3">
	<div style="margin-top:10%;">
		<div class="thumbnail">
	      <img src="../images/No_Image.jpg">
	      <div class="caption">
	        <h3 id="account_text"></h3>
	        <p id="name_text"></p>
	      </div>
	    </div>
	    <div class="input-group">
	      <span class="input-group-addon" >Account</span>
	      <input type="text" class="form-control" id="account" placeholder="Student ID...">
	    </div>
	    <div class="alert alert-danger" role="alert" id="accountAlert" style="margin-top:2%;display:none;"></div>
    </div>
</div>
<div class="col-xs-9">
	<div style="margin-top:3%;">
	    <div class="input-group">
	      <span class="input-group-addon">Book Code</span>
	      <input type="text" class="form-control" id="bookCode" placeholder="Book Code...">
	      <!-- <span class="input-group-btn">
	        <button class="btn btn-default" type="button">Go!</button>
	      </span> -->
	    </div>
	    <div class="alert alert-danger" role="alert" id="bookAlert" style="margin-top:2%;display:none;"></div>
	    <div class="panel panel-danger" id="alert" style="margin-top:2%;display:none;">
	      <div class="panel-heading">
	        <h3 class="panel-title">check in is baned!</h3>
	      </div>
	    </div>
	    <div class="panel panel-danger" id="overdue" style="margin-top:2%;display:none;">
	      <div class="panel-heading">
	        <h3 class="panel-title">QQ! Some book were overdue !!!!!</h3>
	      </div>
	      <div class="panel-body">
	        <table class="table table-striped" id="overdueList">
			   <thead>
		        <tr>
		          <th>#</th>
		          <th>Check in Date</th>
		          <th>Due Date</th>
		          <th>Overdue Days</th>
		          <th>Fine</th>
		        </tr>
		      </thead>
		      <tbody>
		      </tbody>
			</table>
			<button class="btn btn-default" id="payment">Payment</button>
	      </div>
	    </div>
	    <div class="panel panel-primary" id="log" style="margin-top:2%;display:none;">
	      <div class="panel-heading">
	        <h3 class="panel-title">Check In</h3>
	      </div>
	      <div class="panel-body">
	        <table class="table table-striped" id="blog">
			   <thead>
		        <tr>
		          <th>#</th>
		          <th>Book Name</th>
		          <th>Check In Time</th>
		        </tr>
		      </thead>
		      <tbody>
		      </tbody>
			</table>
	      </div>
	    </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#account_text,#name_text").hide();
	});
	$("#account").inputmask({
		mask:'999999999',
		oncomplete:function(){
			$.ajax({
				type:"POST",
				url:'../control/checkAccount.php',
				data:'account='+$("#account").val(),
				dataType:'json',
				success:function(data){
					console.log(data);
					$("#overdue").hide();
					if(data[0]['status']=='success'){
						$("#account").val('');
						$("#account_text").text(data[0]['account']).show();
						$("#payment").attr("data",data[0]['account']);
						$("#name_text").text(data[0]['name']).show();
						$("#log").slideDown().show(500);
						$("#accountAlert").slideUp(200).hide();
						updateLog();
					}
					else{
						$("#accountAlert").slideDown(200).show().html("<strong>Oops!</strong> The account doesn't exist !");
						$("#account_text").text('');
						$("#account").val('');
						$("#account_text,#name_text,#log").hide();
					}
					if(data[1]){
						$("#overdue").find("tbody tr").remove();
						$("#overdue").find('.panel-title').html('down is account :<strong>'+data[0]['account']+'</strong> fine list');
						for(var key in data[1]){
							$("#overdue tbody").append('<tr class="warning"><td>'+data[1][key]['title']+'</td><td>'+data[1][key]['checkin']+'</td><td>'+data[1][key]['checkout']+'</td><td>'+data[1][key]['due']+'</td><td>'+data[1][key]['days']+'days</td><td>$'+data[1][key]['fine']+'</td></tr>');
						}
					}
					if(data[2]){
						$("#overdue tbody").append('<tr class="danger"><td colspan="4"></td><td><strong>Total</strong></td><td><span class="glyphicon glyphicon-usd"></span><strong>'+data[2]['total']+'</strong></td></tr>');
						$("#overdue").show();
					}
				}
			});
		}
	});
	$("#bookCode").inputmask({
		mask:'9999999999',
		oncomplete:function(){
			if($("#account_text").text()!=''){
				$.ajax({
					type:"POST",
					url:'../control/insertLog.php',
					data:'action=checkio&bookCode='+$("#bookCode").val()+"&account="+$("#account_text").text(),
					dataType:'json',
					success:function(data){
						console.log(data);
						$("#bookCode").val('');
						if(data[0]['status']=='error'){
							$("#bookAlert").slideDown(200).html("<strong>Oops!</strong> No this book code!").show();
							$("#bookCode").val('');
						}else if(data[0]['status']=='success'){
							$("#bookAlert,#overdue").slideUp(200).hide();
							updateLog();
							messageRefresh();
						}else if(data[0]['status']=='ban'){
							$("#alert").slideDown(300).show().delay(800).slideUp(300);
						}
					}
				});
			}else{
				$("#bookCode").val('');
				$("#accountAlert").slideDown(200).show().html("<strong>Oops!</strong> No Account !");
			}
		}
	})
	var updateLog=function(){
		$.ajax({
			type:"POST",
			url:'../control/getLog.php',
			data:"action=checkio&account="+$("#account_text").text(),
			dataType:'json',
			success:function(data){
				console.log(data);
				$("#blog tbody tr").remove();
				for(var key in data){
					var order=parseInt(key)+1;
					$("#log tbody").append('<tr><td>'+order+'</td><td>'+data[key]['title']+'</td><td>'+data[key]['time']+'</td></tr>');
				}
			}
		});
	}
	$("#payment").click(function(){
		$.ajax({
			type:"POST",
			url:'../control/updateFine.php',
			data:"account="+$(this).attr('data'),
			success:function(data){
				$("#overdue").slideUp(500);
				updateLog();
			}
		});
	})
</script>