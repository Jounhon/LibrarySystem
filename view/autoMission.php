<div class="col-xs-4" align="left">
	<p class="text-muted">現在時間</p>
	<div class="time" style="margin-top:5%;"></div><br>
	<p class="text-muted">距離自動更新時間</p><br>
	<div class="countdown" style="margin-top:5%;"></div>
</div>
<div class="col-xs-7" id="logs" align="left">
</div>


<script type="text/javascript">
	$(document).ready(function () {
		// Grab the current date
		var currentDate = new Date();
		// Set some date in the future. In this case, it's always Jan 1
		var futureDate  = new Date(currentDate.getFullYear(),currentDate.getMonth(), currentDate.getDate()+1);
		// Calculate the difference in seconds between the future and current date
		var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;

		var countdown=$(".countdown").FlipClock(diff,{
			clockFace: 'HourlyCounter',
			countdown: true,
		    autoStart: true,
		    callbacks: {
	        	interval: function() {
	        		var time = this.factory.getTime().time;
	        		console.log(time);
	        		if(time==53360) {
		        		missionStart();
		        	}
	        	}
	        }
		});
		var date = new Date();
		var time=$(".time").FlipClock(date,{
			clockFace: 'TwentyFourHourClock',
			autoStart: true,
		})
	    //$('#fancyClock').tzineClock();
	   // ShowTime();
	})
	function missionStart(){
		$.ajax({
			type:"POST",
			url:'../control/autoMission.php',
			data:'',
			dataType:'json',
			success:function(data){
				messageRefresh();
				console.log(data);
				$("#logs").find('div').remove();
				for(var key in data){
					var color='default';
					switch(data[key]['status']){
						case 'done':
							color='primary';
						break;
						case 'success':
							color='success';
						break;
						case 'fine':
							color='info';
						break;
						case 'overdue':
							color='danger';
						break;
						case 'cancel':
							color='warning';
						break;
					};
					$("#logs").append('<div class="panel panel-'+color+'"><div class="panel-heading"><strong>'+data[key]['status']+"</strong>  "+data[key]['log']+'</div></div>');
				}
			}
		})
	}
</script>