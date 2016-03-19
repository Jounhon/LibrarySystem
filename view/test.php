<?php
	require_once '../control/conn.php';
		$datetime=date("Y-m-d H:i:s",mktime(date('H')+8));
		$datetime=date_create($datetime);
		echo date_format($datetime,"Y-m-d");
		echo "------------";
?>


<script type="text/javascript" src="../script/jquery-2.1.4.min.js"></script>
<p id="time"></p>
<?php
	$datetime=date("Y-m-d H:i:s",mktime(date('H')+8));
	echo $datetime."<br>"; 

	$due=date('Y-m-d', strtotime("+30 days"));
	
	echo $due;
?>

<script type="text/javascript">
	$(document).ready(function () {
	    ShowTime();
	})
	function ShowTime() {
	    var NowDate = new Date();
	    var y = NowDate.getFullYear();
	    var M = ("0" + (NowDate.getMonth()+1)).slice(-2);
	    var d = ("0" + NowDate.getDate()).slice(-2);
	    var h = ("0" + NowDate.getHours()).slice(-2);
	    var m = ("0" + NowDate.getMinutes()).slice(-2);
	    var s = ("0" + NowDate.getSeconds()).slice(-2);
	    $("#time").html(y + "-" + M + "-" + d + "<br>" + h + ':' + m + ':' + s);
	    setTimeout('ShowTime()', 1000);
	}
</script>