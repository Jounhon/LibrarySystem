<?php
	session_start();
	if(!isset($_SESSION['userAuthority'])||$_SESSION['userAuthority']=='1') echo "<script>window.location='http://localhost'</script>";
	require_once '../control/conn.php';	
?>
<script src="script/ManageBook.js"></script>
<div class="starter-template" >

	<Button type="button" class="pull-right btn btn-primary" id="addBook">Add New Book</Button>
	<Button type="button" class="pull-right btn btn-primary glyphicon glyphicon-refresh" onclick="changeView('ManageBook')"></Button>
	<div class="input-group pull-left" style="width:70%;margin-right:5%;"> 
		<span class="input-group-addon">Search</span>
	    <input id="filter" type="text" class="form-control" placeholder="Type here...">
	</div>
	<?php include '../view/bookInsert.php';?>
	<div id="copyBox" class="pull-left" style="width:40%;position:relative;display:none;">
		<button type="button" class="btn btn-default glyphicon glyphicon-remove pull-right" style="margin-top:4%;" id="copyBoxClose"></button>
		<div class="outCopybox"></div>
	</div>
	<div id="Booklist"><?php include '../view/bookList.php';?></div>
</div>
<?php include '../view/bookModal.php';?>
  <script src="bootstrap-fileinput-master/js/fileinput.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		
		initCategory();
		setSearch();
	    $("#book-new-isbn").inputmask({
	   		mask:"999-999-999-999-9",
	   		onincomplete: function () {
	   			var select=$(this).data('select');
	   			if($(this).parent().find('div.alert-danger')) erorrCount[select]=0;
	   			$(this).parent().removeAttr('class').find('span').remove();
	   			$(this).parent().find('div.alert').remove();
	   			if(erorrCount[select]==0)
					$(this).parent().attr('class','has-warning has-feedback').append($('<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(warning)</span><div class="alert alert-warning" role="alert" style="margin-top:10px;">ISBN is Imcomplete !!</div>'))
				erorrCount[select]++;
	   		},
	   		oncomplete: function () {
	   			var select=$(this).data('select');
	   			var isbn_part=$(this).val().split('-');
	   			var isbn="";
	   			for(var i=0;i<isbn_part.length;i++) isbn+=isbn_part[i];
	   			var sum=0,i=0;
	   			for(i;i<isbn.length-1;i++){
	   				if(i%2==0) sum+=parseInt(isbn[i]);
	   				else sum+=parseInt(isbn[i])*3;
	   			}
	   			if((10-sum%10)==parseInt(isbn[i])){
		   			erorrCount[select]=0;
					$(this).parent().removeAttr('class').find('span').remove();
					$(this).parent().find('div.alert').remove();
					$(this).parent().attr('class','has-success has-feedback').append($('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span><span id="inputSuccess2Status" class="sr-only">(success)</span>'))
	   			}
	   			else{
	   				if($(this).parent().find('div.alert-warning')) erorrCount[select]=0;
	   				$(this).parent().removeAttr('class').find('span').remove();
	   				$(this).parent().find('div.alert').remove();
		   			if(erorrCount[select]==0)
						$(this).parent().attr('class','has-error has-feedback').append($('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(error)</span><div class="alert alert-danger" role="alert" style="margin-top:10px;">ISBN is Wrong !!</div>'))
					erorrCount[select]++;
	   			}
	   		}
	   	});  //static mask
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

	});
</script>