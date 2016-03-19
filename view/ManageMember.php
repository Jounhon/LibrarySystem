<?php
	session_start();
	if(!isset($_SESSION['userAuthority'])||$_SESSION['userAuthority']=='1') echo "<script>window.location='http://localhost'</script>";
	require_once '../control/conn.php';
	$query=mysqli_query($link, 'SELECT * FROM `member` ORDER BY `authority` DESC');
	$rows=mysqli_num_rows($query);
?>

<div class="starter-template" style="width:75%;">
	<Button type="button" class="pull-right btn btn-primary glyphicon glyphicon-refresh" onclick="changeView('ManageMember')"></Button>
	<div class="input-group pull-right" style="width:90%;margin-right:5%;"> 
		<span class="input-group-addon">Search</span>
	    <input id="filter" type="text" class="form-control" placeholder="Type here...">
	</div>
	<table class="table table-hover" id="memberTable">
  		<thead class="table table-striped">
	  		<tr>
		        <th data-sort="int"><a>#Account</a></th>
		        <th data-sort="string"><a>Name</a></th>
		        <th data-sort="string"><a>Email</a></th>
		        <th>Authority</th>
		    </tr>
	    </thead>
	    <tbody class="searchable">
		    <?php
		    	while ($res=mysqli_fetch_row($query)){
		    		switch($res[4]){
		    			case '1': $color='success';break;
		    			case '2': $color='info';break;
		    			case '3': $color='danger';break;
		    		}
		    ?>
		    <tr class="<?php echo $color;?>">
		    	<td><?php echo $res[1]?></td>
		    	<td class="item"><?php echo $res[0]?></td>
		    	<td class="item"><?php echo $res[3]?></td>
		    	<td>
		    		<select class="form-control input-sm" id="user-authority" onchange="SaveAuthority(this,'<?php echo "$res[1]"?>')" <?php if(($res[4]=='3'&&$_SESSION['userAuthority']!='3')||$_SESSION['userAccount']==$res[1]) echo "disabled='disabled'";?>>
		            	<option value="1" <?php if($res[4]=='1') echo "selected";?> >Staff</option>
		            	<option value="2" <?php if($res[4]=='2') echo "selected";?> >Manager</option>
		            	<?php if($_SESSION['userAuthority']=='3'){ ?>
						<option value="3" <?php if($res[4]=='3') echo "selected";?> >Administrator</option>
						<?php }?>
		            </select>
		    	</td>
		    </tr>
		    <?php }?>
		</tbody>
	</table>
	<nav>
		<ul class="pagination">
			<li onclick="goPrePage()">
		      <a href="#" aria-label="Previous">
		        <span aria-hidden="true">&laquo;</span>
		      </a>
		    </li>
		    <?php for($i=1;$i<=ceil($rows/10);$i++){?>
		    <li id="page"><a href="#"><?php echo $i;?></a></li>
		    <?php }?>
		    <li onclick="goNextPage()">
		      <a href="#" aria-label="Next">
		        <span aria-hidden="true">&raquo;</span>
		      </a>
		    </li>	
		</ul>
	</nav>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		(function ($) {
	        $('#filter').keyup(function () {
	            var rex = new RegExp($(this).val(), 'i');
	            $('.searchable tr').hide();
	            $('.searchable tr').filter(function () {
	                return rex.test($(this).text());
	            }).show();
	        })
	        $("#filter").focus(function(){
	        	$(".pagination").hide();
	        });
	        $("#filter").focusout(function(){
	        	if($(this).val()==''){
	        		$(".pagination").show();
		        	var index=$('.pagination li.active').index();
		        	diviseList(index);
	        	}
	        })
	    }(jQuery));
	})
	var diviseList=function(index){
		var pages=$('.pagination li').size();
		$('.pagination li').removeAttr('class');
		if(index==1) $('.pagination li:eq(0)').attr('class','disabled');
		if(index==pages-2) $('.pagination li:eq('+(pages-1)+')').attr('class','disabled');
		$('.pagination li:eq('+index+')').attr('class','active');
		$('#memberTable tr').hide();
		$('#memberTable tr:eq(0)').show();
		for(var i=1+(index-1)*10;i<=index*10;i++){
			$('#memberTable tr:eq('+i+')').show();
		}

	}
	$(function(){
		$('.pagination li:eq(0)').attr('class','disabled');
		$('.pagination li:eq(1)').attr('class','active');
		var index=$('.pagination li.active').index();
		var table=$('#memberTable').stupidtable();
		table.on("beforetablesort", function (event, data) {
			$('#memberTable tr').show();
		});
		table.on("aftertablesort", function (event, data) {
			var index=$('.pagination li.active').index();
			diviseList(index);
		});
		diviseList(index);
	});
	
	$('.pagination li#page').click(function(){
		var index=$(this).index();
		diviseList(index);
	});

	var goPrePage=function(){
		var name=$('.pagination li:eq(0)').attr('class');
		if(name!='disabled'){
			var index=$('.pagination li#page.active').index();
			diviseList(index-1);
		}
	}
	var goNextPage=function(){
		var pages=$('.pagination li').size();
		var name=$('.pagination li:eq('+(pages-1)+')').attr('class');
		if(name!='disabled'){
			var index=$('.pagination li#page.active').index();
			diviseList(index+1);
		}
	}

	var  SaveAuthority=function(e,account){
	$.ajax({
		type:"POST",
		url:'../control/updateAuthority.php',
		data:'account='+account+"&a="+e.value,
		success:function(data){
			var color;
			switch(e.value){
    			case '1': color='success';break;
    			case '2': color='info';break;
    			case '3': color='danger';break;
    		}
    		$(e).closest("tr").removeAttr('class').attr('class',color);
		}
	}).done(function(data){
		console.log(data);
	});
}
	
</script>