<?php
	session_start();
	if(!isset($_SESSION['userAccount'])) echo "<script>window.location='http://localhost'</script>";
	require_once '../control/conn.php';
	$query=mysqli_query($link, 'SELECT * FROM `message` WHERE `account`="'.$_SESSION['userAccount'].'" AND `delete` IS NULL ORDER BY id DESC');
	$count=0;
	$rows=mysqli_num_rows($query);
?>
<script type="text/javascript" src="bootstrap-toggle-master/js/bootstrap-toggle.js"></script>
<script type="text/javascript" src="bootstrap-star-ranting/js/star-rating.js"></script>
<div class="panel panel-default">
  <div class="panel-body">
    <table style="width:100%;">
    	<tr>
    		<td style="width:20%;">
    			<div class="checkbox" style="padding:0;">
				  <label>
				    <input type="checkbox" id="allCheckbox" value="all" aria-label="..."> All
				  </label>
				</div>
    		</td>
    		<td style="width:80%;" align="right"><button class="btn btn-primary btn-sm" id="deleteMessage" <?php if($rows==0)echo "disabled";?>><span class=" glyphicon glyphicon-trash"></span> Delete</button></td>
    	</tr>
	</table>
  </div>
</div>

<div id="messages">
	<?php if($rows==0){?>
		<div class="alert alert-info">
			No New Messages now.
		</div>
	<?php }?>
	<?php while($res=mysqli_fetch_row($query)){
		$count++;
		$date = date_create($res[4]);
	?>
	<div class="panel-group" id="messageList">
	  <div class="panel panel-default">
	  	<div class="panel-body">
		    <table style="width:100%;">
		    	<tr>
		    		<td style="width:10%;">
		    			<div class="checkbox">
		    				<label><input type="checkbox" id="IdCheckbox" name="IdCheckbox" value="<?php echo $res[0];?>" aria-label="..."></label>
						</div>
		    		</td>
		    		<td style="width:75%;cursor:pointer;<?php if($res[5]!='') echo 'color:#ccc';?>" class="tdClick" data-toggle="collapse" data-parent="#messageList" href="#collapse_<?php echo $res[0];?>"><?php echo $res[2]?></td>
		    		<td style="width:15%;"><?php echo date_format($date, 'Y-m-d');?></td>
		    	</tr>
			</table>
		</div>
	  </div>
	  <div id="collapse_<?php echo $res[0];?>" class="panel-collapse collapse ">
	    <div class="well" align="left">
		  	<?php if($res[7]!='score'){ echo $res[3]; } ?>
		</div>
	  </div>
	</div>
	<?php }
		echo '<script type="text/javascript">$(".bootstrapToggle").bootstrapToggle()</script>'
	?>
</div>
<script type="text/javascript">
	$("#allCheckbox").click(function(){
		$("input:checkbox").not(this).prop('checked', this.checked);
	});
	$("td.tdClick").click(function(){
		var id=$(this).attr('href').split('_')[1];
		if($(this).css('color')!='rgb(204, 204, 204)'){
			$(this).css('color','rgb(204, 204, 204)');
			$.ajax({
				type:"POST",
				url:'../control/updateMessage.php',
				data:'action=read&id='+id,
				success:function(data){
					messageRefresh();
				}
			});
		}
	})
	$("#deleteMessage").click(function(){
		var ids=$('input[name="IdCheckbox"]:checkbox:checked').map(function() {
			$(this).closest("#messageList").animate({
			    opacity: 0,
			    marginLeft: "80%",
			  }, 500, function() {
			    $(this).remove();
			  });
  			return $(this).val();
		}).get().join(',');
		$.ajax({
			type:"POST",
			url:'../control/updateMessage.php',
			data:'action=delete&ids='+ids,
			success:function(data){
				messageRefresh();
				var count=0;
				$(".panel-group").each(function(){
					count++;
				});
				if(count==0){
					$("#deleteMessage").attr('disabled',true);
					$("#messages").append('<div class="alert alert-info">No New Messages now.</div>');
				}
				messageRefresh();
			}
		});
	})
	$(".well").each(function(){
		$(this).find('button').click(function(){
			var Datas={
				isbn:$(this).attr('dataISBN'),
				comment:$(this).parent().find('textarea').val(),
				rate:$(this).parent().find('.rating').val(),
				anonymous:$(this).parent().find('input:checkbox').prop('checked'),
				account:"<?php echo $_SESSION['userAccount']?>",
				msgId:$(this).parent().parent().attr('id').split('_')[1]
			};
			var _this=$(this);
			$.ajax({
				type:"POST",
				url:'../control/insertComment.php',
				data:{data:Datas},
				success:function(data){
					_this.parent().html("<b>已評過分了。</b>");
				}
			});
		})
	})
</script>
