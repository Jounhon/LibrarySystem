<div class="col-xs-3">
	<div class="panel panel-default">
	  <div class="panel-heading"><span class="glyphicon glyphicon-search"></span> Search</div>
	  <div class="panel-body">
	  	<form class="form-inline" onsubmit="return false;">
          <input type="text" placeholder="Key Word" class="form-control pull-left" id="searchText" style="width:80%;">
          <button type="button" class="btn btn-success glyphicon glyphicon-search" id="searchAll"></button>
          <p style="color:#bbb;" class="pull-left">Title,ISBN,author,publisher</p>
       </form>
	  </div>
	</div>
	<div class="panel panel-default">
	  <div class="panel-heading"><span class="glyphicon glyphicon-th-list"></span> Category</div>
	  <div class="panel-body">
	  	<select class="form-control " id="classification"></select>
		<select class="form-control" id="subclassification" style="margin-top:10px;"></select>
	  </div>
	</div>
</div>
<div class="col-xs-9" id="tabs">
	<ul class="nav nav-tabs">
	  <li role="presentation" class="active" id="li_new">
	  	<a data-toggle="tab" href="#new">
	  		<span class="glyphicon glyphicon-fire"></span> New
	  	</a>
	  </li>
	  <li role="presentation" id="li_recom">
	  	<a data-toggle="tab" href="#recom">
	  		<span class="glyphicon glyphicon-thumbs-up"></span> Recommend
	  	</a>
	  </li>
	  <li role="presentation" id="li_class">
	  	<a data-toggle="tab" href="#class">
	  		<span class="glyphicon glyphicon-th-list"></span> Category
	  	</a>
	  </li>
	  <li role="presentation" id="li_search">
	  	<a data-toggle="tab" href="#search">
	  		<span class="glyphicon glyphicon-search"></span> Search
	  	</a>
	  </li>
	</ul>
	<div class="tab-content">
	    <div class="tab-pane fade in active" id="new"><div class="outbox"></div></div>
	    <div class="tab-pane fade" id="recom">
	    	<div class="outbox">Comming Soon...</div>
	    </div>
	    <div class="tab-pane fade" id="class"><div class="outbox"></div></div>
	    <div class="tab-pane fade" id="search"><div class="outbox"></div></div>
	</div>
</div>

<div class="modal" id="BookDetailModal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="ModalTitle"></h4>
      </div>
        <div class="modal-body row">
        <div class="col-xs-5" >
        	<input id='rateStar' value='0' type='number' class='rating' >
        	<table class="table table-bordered">
        		<tr><td colspan ="2" align="center"><img src="../images/No_Image.jpg"></td></tr>
        		<tr>
        			<td><label class="control-label">ISBN</label></td>
        			<td><label class="control-label" id="lb-isbn">xxxx</label></td>
        		</tr>
        		<tr>
        			<td ><label class="control-label">Classification</label></td>
        			<td ><label class="control-label" id="lb-class">xxxx</label></td>
        		</tr>
        		<tr>
        			<td ><label class="control-label">Author</label></td>
        			<td ><label class="control-label" id="lb-author">xxxx</label></td>
        		</tr>
        		<tr>
        			<td ><label class="control-label">Publisher</label></td>
        			<td ><label class="control-label" id="lb-pub">xxxx</label></td>
        		</tr>
        		<tr>
        			<td ><label class="control-label">Date</label></td>
        			<td ><label class="control-label" id="lb-date">xxxx</label></td>
        		</tr>
        	</table>
        </div>
        <div class="col-xs-7">
        	<div align="rignt">
        		<span class="label label-success">on-shelf</span>
        		<span class="label label-primary">on-hold</span>
	        	<span class="label label-danger">on-loan</span>
	        	<span class="label label-warning">on-loan&on-hold</span>
        	</div>
        	<div align="left" id="copyStatus"></div>
        	<div id="comments" style="margin-top:2%;max-height:80%;overflow:auto;"></div>
        </div>       	
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript" src="bootstrap-star-ranting/js/star-rating.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		initSelect();
		changeBookList('new','','');
	})
	initStarRate();
	function initStarRate(){
		$(".rating").rating({
			'showCaption':true,
			'stars':'5',
			'min':'0', 
			'max':'5', 
			'step':'0.1', 
			'size':'xs',
			'showClear':false,
			'defaultCaption':'{rating}',
			'readonly':'true',
			'starCaptions': {0:'0'} 
		})
	}
	var changeBookList=function(id,cid,search){
		$.ajax({
			type:"POST",
			url:'../control/getBook.php',
			data:'action='+id+"&cid="+cid+"&search="+search,
			dataType:'json',
			success:function(data){
				$("#tabs div#"+id+" .outbox .content_box").remove();
				for(var key in data){
					$("#tabs div#"+id+" .outbox").append('<div class="content_box" style="margin-top:2%;" data-toggle="modal" data-target="#BookDetailModal" data-isbn="'+data[key]['isbn']+'"><div class="thumbnail"><a href="#"><img src="../images/No_Image.jpg"></a><div class="caption"><a href="#"><h3>'+data[key]['title']+'</h3></a></div></div></div>');
				}
				if(data.length==0&&id=='search'){
					$("#tabs div#"+id+" .outbox").append('<div class="content_box" style="margin-top:2%;"><div class="alert alert-warning" role="alert">Not found the BOOKs matching the Key Word of search.</div></div>');
				}
			}
		});
	}
	$('#BookDetailModal').on('show.bs.modal', function (event) {
		var div = $(event.relatedTarget) ;
		var isbn = div.data('isbn');
		var modal = $(this);
		$.ajax({
			  type:"POST",
			  url:'../control/getBook.php',
			  data:'action=getDetail&isbn='+isbn+'&account='+<?php if(isset($_SESSION["userAccount"]))echo $_SESSION['userAccount'];else echo "null";?>,
			  dataType:'json',
			  success:function(data){
			    console.log(isbn+"**");
			    console.log(data);
			    $('#BookDetailModal #ModalTitle').text(data[0]['title']);
			    $('#BookDetailModal #rateStar').rating('update', data[0]['rate']);
			    $('#BookDetailModal #lb-isbn').text(data[0]['isbn']);
			    $('#BookDetailModal #lb-date').text(data[0]['date']);
			    $('#BookDetailModal #lb-pub').text(data[0]['publisher']);
			    $('#BookDetailModal #lb-class').html(data[0]['mc']+" -<br>"+data[0]['sc']);
			    var author='';
			    for(var key in data[1]){
			    	if(key>0) author+="<br>";
			    	author+=data[1][key];
			    }
			    $('#BookDetailModal #lb-author').html(author);
			    $("#copyStatus").find('button').remove();
			    for(var key in data[2]){
			    	$("#copyStatus").append('<button class="btn btn-sm btn-default box" data="'+data[2][key]['code']+'" style="margin-right:3%;" '+data[2][key]['disabled']+'><span class="glyphicon glyphicon-record" style="color:'+data[2][key]['status_color']+';"></span> Copy No.'+data[2][key]['number']+'</button>');
			    }
				$("#copyStatus button.btn").click(function(){
					holdBook($(this));
				});
				if(data[3]){
					$("#comments div.panel").remove();
					for(var key in data[3]){
						$("#comments").append('<div class="panel panel-default"><div class="panel-heading" align="right"><input value="'+data[3][key]['rate']+'" type="number" class="rating" data-rtl=1 ></div><div class="panel-body"><p align="left">'+data[3][key]['comment']+'</p><p align="right">'+data[3][key]['name']+'</p></div></div>');
					}
					initStarRate();
				}
				else{
					$("#comments div.panel").remove();
					$("#comments").append('<div class="panel panel-default"><div class="panel-body">目前沒有評論</div></div>');
				}
			  }
		});
	});
	var holdBook=function(_this){
		console.log(_this.attr('data'));
		$.ajax({
		  type:"POST",
		  url:'../control/insertLog.php',
		  data:'action=reserve&code='+_this.attr('data')+'&account='+<?php if(isset($_SESSION["userAccount"]))echo $_SESSION['userAccount'];else echo "null";?>,
		  success:function(data){
		  	if(data=='success'){
		  		$.ajax({
				  type:"POST",
				  url:'../control/getBook.php',
				  data:'action=getCopy&code='+_this.attr('data')+'&account='+<?php if(isset($_SESSION["userAccount"]))echo $_SESSION['userAccount'];else echo "null";?>,
				  dataType:'json',
				  success:function(data){
				  	$("#copyStatus").find('button').remove();
				    for(var key in data){
				    	$("#copyStatus").append('<button class="btn btn-sm btn-default box" data="'+data[key]['code']+'" style="margin-right:3%;" '+data[key]['disabled']+'><span class="glyphicon glyphicon-record" style="color:'+data[key]['status_color']+';"></span> Copy No.'+data[key]['number']+'</button>');
				    }
					$("#copyStatus button.btn").click(function(){
						holdBook($(this));
					});
				  }
				})
		  	}
		  }
		})
	}
	$("#searchText").keypress(function(e){
		var code = (e.keyCode ? e.keyCode : e.which);
  		if (code == 13){
  			$(".nav-tabs li,div.tab-pane").removeClass('active');
			$("li#li_search,div#search").addClass('in active');
			changeBookList('search','',$("#searchText").val());
  		}
	})
	$("#searchAll").click(function(){
		$(".nav-tabs li,div.tab-pane").removeClass('active');
		$("li#li_search,div#search").addClass('in active');
		changeBookList('search','',$("#searchText").val());
	})
	var initSelect=function(){
		$("#subclassification option,#classification option").remove();
		$.ajax({
			type:"POST",
			url:'../control/getCategory.php',
			data:'',
			dataType:'json',
			success:function(data){
				$("#classification").append($("<option></option>").attr("value", 'all').text('Select Category'));
				for(var key in data){
					$("#classification").append($("<option></option>").attr("value", data[key]['id']).text(data[key]['name']));
				}
				$('#subclassification').attr('disabled','true');
				$("#subclassification").append($("<option></option>").attr("value", '').text('Select SubCategory'));
			}
		})
	}
	$(".nav-tabs li").click(function(){
		var id=this.id.split('_')[1];
		changeBookList(id,'','#');
		if(id!='class') initSelect();
	});
	// $('.nav-tabs li').mouseover(function(){
	// 	var id=this.id.split('_')[1];
	// 	$(".nav-tabs li,div.tab-pane").removeClass('in active');
	// 	$("li#li_"+id+",div#"+id).addClass('in active');
	// 	changeBookList(id,'','#');
	// 	if(id!='class') initSelect();
	// })
	$("#classification").change(function(){
		$(".nav-tabs li,div.tab-pane").removeClass('active');
		$("li#li_class,div#class").addClass('in active');
		if($(this).val()!='all'){
			$("#subclassification option").remove();
			$("#subclassification").removeAttr("disabled");
		  	$.ajax({
				type:"POST",
				url:'../control/getSubCategory.php',
				data:'id='+$("#classification").val(),
				dataType:'json',
				success:function(data){
					$("#subclassification").append($("<option></option>").attr("value", "").text("Select SubCategory"));
					for(var key in data){
						$("#subclassification").append($("<option></option>").attr("value", data[key]['id']).text(data[key]['name']));
					}
				}
		  	})
		}
		else{
			$("#subclassification").attr("disabled",'true');
		}
		changeCategory($("#classification").val());
	})
	$("#subclassification").change(function(){
		$(".nav-tabs li,div.tab-pane").removeClass('active');
		$("li#li_class,div#class").addClass('in active');
		changeCategory($("#subclassification").val());
	})
	var changeCategory = function(classIds){
		changeBookList('class',classIds,'');
	}
</script>
