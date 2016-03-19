<?php
	session_start();
	if(!isset($_SESSION['userAuthority'])) echo "<script>window.location='http://localhost'</script>";
	require_once '../control/conn.php';
?>
<ul class="nav nav-pills">
	<li role="presentation" class="active" id="s_total"><a href="#">Total</a></li>
  	<li role="presentation" class=""  id="s_om"><a href="#">Past One Month</a></li>
  	<li role="presentation" class="" id="s_tm"><a href="#">Past Three Month</a></li>
  	<li role="presentation" class="" id="s_hy"><a href="#">Past Half Year</a></li>
  	<li role="presentation" class="" id="s_oy"><a href="#">Past One Year</a></li>
</ul>
<div class="panel panel-primary" style="margin-top:2%;">
	<div class="panel-heading">
	    <h3 class="panel-title"><?php echo $_SESSION['userName']."'s library history";?></h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped" id="booklog">
		    <thead>
		        <tr>
		          <th data-sort="int"><a>#</a></th>
		          <th data-sort="string"><a>Book Name</a></th>
		          <th data-sort="string"><a>Check In Time</a></th>
		          <th data-sort="string"><a>Check Out Time</a></th>
		          <th data-sort="string"><a>Hold Time</a></th>
		        </tr>
		    </thead>
		    <tbody>
			</tbody>
		</table>
    </div>
</div>
<script type="text/javascript">
	var checkTime=function(datetime){
		if(datetime['month']<1){
			datetime['month']=12;
			datetime['year']-=1;
		}
		return datetime;
	}
	var changeLog=function(){
		var id=$('ul.nav-pills li.active').attr('id');
		var now=new Date();
		var datetime={
			year:now.getFullYear(),
			month:now.getMonth()+1,
			day:now.getDate()
		}
		
		switch(id){
			case 's_total':
				datetime=null;
			break;
			case 's_om':
				datetime['month']-=1;
			break;
			case 's_tm':
				datetime['month']-=3;
			break;
			case 's_hy':
				datetime['month']-=6;
			break;
			case 's_oy':
				datetime['year']-=1;
			break;
		}
		if(datetime!=null){
			datetime=checkTime(datetime);
			strDatetime=datetime['year']+"-"+( datetime['month'] < 10 ? '0' : '')+datetime['month']+"-"+( datetime['day'] < 10 ? '0' : '')+datetime['day']+" 00:00:00";
		}else strDatetime=null;
		$.ajax({
			type:"POST",
			url:'../control/getLog.php',
			data:'action=memberlog&datetime='+strDatetime+"&account="+<?php echo $_SESSION['userAccount']?>,
			dataType:'json',
			success:function(data){
				$("table#booklog tbody tr").remove();
				for(var key in data){
					var order=parseInt(key)+1;
					$("table#booklog tbody").append('<tr><td>'+order+'</td><td>'+data[key]['title']+'</td><td>'+(data[key]['in']==null?'':data[key]['in'])+'</td><td>'+(data[key]['out']==null?'':data[key]['out'])+'</td><td>'+(data[key]['hold']==null?'':data[key]['hold'])+'</td></tr>');
				}
			}
		});

	}
	$(document).ready(function(){
		$('table#booklog').stupidtable();
		changeLog();
	})
	$('ul.nav-pills li').click(function() {
		$("ul.nav-pills li").removeClass('active');
		$(this).addClass('active');
		changeLog();
	})
</script>