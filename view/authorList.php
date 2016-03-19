<?php
	//session_start();
	if(!isset($_SESSION['userAuthority'])||$_SESSION['userAuthority']=='1') echo "<script>window.location='http://localhost'</script>";
	require_once '../control/conn.php';
	$query=mysqli_query($link, 'SELECT * FROM `author` ORDER BY `id` DESC');
	$rows=mysqli_num_rows($query);
?>
<div class="input-group pull-right"> 
	<span class="input-group-addon">Search</span>
    <input id="filter" type="text" class="form-control" placeholder="Type here...">
</div>
<table class="table table-hover" id="BookTable">
	<thead class="table table-striped">
		<tr>
	        <th data-sort="int"><a>#Author ID</a></th>
	        <th data-sort="string"><a>Name</a></th>
	        <th data-sort="string"><a>Organization</a></th>
	        <th></th>
   		</tr>
	</thead>
	<tbody  class="searchable">
		<?php
			while ($res=mysqli_fetch_row($query)){
		?>
		<tr>
	    	<td class="item"><?php echo $res[0]?></td>
	    	<td class="item"><?php echo $res[1]?></td>
	    	<td class="item"><?php echo $res[2]?></td>
	    	<td>
	    		<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#EditModal" 
	    			data-name="<?php echo $res[1];?>" 
	    			data-org="<?php echo $res[2];?>" 
	    			data-id="<?php echo $res[0];?>">Edit</button>
	    		<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#DeleteModal"
	    			data-id="<?php echo $res[0];?>"
	    			data-name="<?php echo $res[1];?>">Delete</button>
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

<div class="modal" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ModalTitle">Edit Author Information</h4>
      </div>
      <div class="modal-body">
        <form>
          <table class="table">
          	<tr>
          		<td><label for="recipient-name" class="control-label">Name</label></td>
          		<td><input type="text" class="form-control" id="author-name"></td>
          	</tr>
          	<tr>
          		<td><label for="message-text" class="control-label">Oranization</label></td>
          		<td><input type="text" class="form-control" id="author-org"></td>
          	</tr>
          </table>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveAuthor">Save</button>
      </div>
    </div>
	</div>
</div>

<div class="modal" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="ModalTitle">確定要刪除嗎?</h3>
        <h5 class="modal-title" id="ModalSubTitle">刪了就回不去了</h5><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="deleteAuthor">Delete</button>
      </div>
    </div>
	</div>
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
	        });
	        $("#filter").focus(function(){
	        	$(".pagination").hide();
	        	$('.searchable tr').show();
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
		$('#BookTable tr').hide();
		$('#BookTable tr:eq(0)').show();
		for(var i=1+(index-1)*10;i<=index*10;i++){
			$('#BookTable tr:eq('+i+')').show();
		}
	}
	$(function(){
		$('.pagination li:eq(0)').attr('class','disabled');
		$('.pagination li:eq(1)').attr('class','active');
		var index=$('.pagination li.active').index();
		var table=$('#BookTable').stupidtable();
		table.on("beforetablesort", function (event, data) {
			$('#BookTable tr').show();
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

	$('p').focusout(function(){
		var index=$(".pagination li#page.active").index();
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

	$('#EditModal').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget);
	  var formData={
	  	'name':button.data('name'),
	  	'org':button.data('org'),
	  	'id':button.data('id')
	  }
	  var modal = $(this);
	  modal.find('#author-name').val(formData['name']);
	  modal.find('#author-org').val(formData['org']);
	  modal.find('#saveAuthor').click(function(){
	  	var name=$("#author-name").val();
	  	var org=$("#author-org").val();
	  	$.ajax({
			type:"POST",
			url:"../control/updateAuthor.php",
			data:"name="+name+"&org="+org+"&id="+formData['id'],
			success:function(data){
				$('#EditModal').modal('hide');
				$.ajax({
					type:"POST",
					url:'../view/authorList.php',
					data:'',
					success:function(data){
						$("#authorList").html(data);
					}
				});
			}
		}).done(function(data){
			console.log(data);
		});
	  })
	});
	$('#DeleteModal').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget);
	  var formData={
	  	'name':button.data('name'),
	  	'id':button.data('id')
	  }
	  var modal = $(this);
	  modal.find('#ModalTitle').text('確定要刪除'+formData['name']+'?');
	  modal.find('#deleteAuthor').click(function(){
	  	$.ajax({
			type:"POST",
			url:"../control/deleteAuthor.php",
			data:"id="+formData['id'],
			success:function(data){
				$('#DeleteModal').modal('hide');
				$.ajax({
					type:"POST",
					url:'../view/authorList.php',
					data:'',
					success:function(data){
						$("#authorList").html(data);
					}
				});
			}
		}).done(function(data){
			console.log(data);
		});
	  });
	});
</script>