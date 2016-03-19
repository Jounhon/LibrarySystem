<?php
	require_once '../control/conn.php';	
	$query=mysqli_query($link, 'SELECT * FROM `book` ORDER BY `time` DESC');
	$rows=mysqli_num_rows($query);
	function getPublisher($id,$link){
		$query=mysqli_query($link, 'SELECT `name` FROM `publisher` WHERE `id`="'.$id.'"');
		$fetch=mysqli_fetch_assoc($query);
		return $fetch['name'];
	}
	function getAuthors($isbn,$link){
		$query=mysqli_query($link, 'SELECT a.name FROM `book_author` ba INNER JOIN `author` a ON a.id=ba.author_id WHERE ba.isbn="'.$isbn.'"');
		$count=0;
		$text="";
		while($res=mysqli_fetch_row($query)){
			if($count>0) $text.=", ";
			$text.=$res[0];
			$count++;
		}
		//$text=mysqli_num_rows($query);
		return $text;
	}
	function getClass($id,$link){
		$query=mysqli_query($link, 'SELECT `name` FROM `classification` WHERE `id`="'.$id.'"');
		$fetch=mysqli_fetch_assoc($query);
		return $fetch['name'];
	}
	function getCopyCount($isbn,$link){
		$query=mysqli_query($link, 'SELECT COUNT(*) as count FROM `book_copy` WHERE `isbn`="'.$isbn.'"');
		$fetch=mysqli_fetch_assoc($query);
		return $fetch['count'];		
	}
?>
<table class="table table-hover" id="BookTable">
	<thead class="table table-striped">
		<tr>
			<th data-sort="string"><a>ISBN</a></th>
	        <th data-sort="string"><a>Title</a></th>
	        <th data-sort="string"><a>Category</a></th>
	        <th data-sort="string"><a>Author(s)</a></th>
	        <th data-sort="string"><a>Publisher</a></th>
	        <th data-sort="string"><a>Date</a></th>
	        <th data-sort="int"><a>Copy Count</a></th>
	        <th></th>
   		</tr>
	</thead>
	<tbody  class="searchable">
		<?php
			while ($res=mysqli_fetch_row($query)){
		?>
		<tr>
			<td class="item linkCopy" dataISBN='<?php echo $res[0]?>'><a><?php echo $res[0]?></a></td>
	    	<td class="item linkCopy" dataISBN='<?php echo $res[0]?>'><a><?php echo $res[1]?></a></td>
	    	<td class="item"><?php echo getClass($res[2],$link)." - ".getClass($res[3],$link);?><input type="hidden" id="CATEGORY" value="<?php echo $res[2].','.$res[3];?>"></td>
	    	<td class="item"><?php echo getAuthors($res[0],$link);?></td>
	    	<td class="item"><?php echo getPublisher($res[4],$link);?></td>
	    	<td class="item"><?php echo $res[5]?></td>
	    	<td class="item"><?php echo getCopyCount($res[0],$link);?></td>
	    	<td>
	    		<Button type="button" class="btn btn-primary btn-xs" id="EditBook" 
	    			>Edit</Button>
	    		<button type="button" class="btn btn-info btn-xs" style="margin-top:3px;" data-toggle="modal" data-target="#recordModal" 
	    			data-isbn="<?php echo $res[0];?>">Record</button>
	    		<button type="button" class="btn btn-warning btn-xs" style="margin-top:3px;" data-toggle="modal" data-target="#AddCopyModal"
	    			data-isbn="<?php echo $res[0];?>"
	    			data-name="<?php echo $res[1];?>">Add Copy</button>
	    		<button type="button" class="btn btn-danger btn-xs" style="margin-top:3px;" data-toggle="modal" data-target="#DeleteModal"
	    			data-isbn="<?php echo $res[0];?>"
	    			data-name="<?php echo $res[1];?>">Delete</button>
	    	</td>
	    </tr>
	    <?php }?>
    </tbody>
</table>
<!-- onClick="editBook(<?php //echo '\''.$res[0].'\',\''.$res[1].'\',\''.$res[3].'\',\''.$res[2].'\',\''.$res[4].'\',\''.$res[5].'\'';?>)" -->
<nav>
	<ul class="pagination">
		<li onclick="goPrePage()">
	      <a aria-label="Previous">
	        <span aria-hidden="true">&laquo;</span>
	      </a>
	    </li>
	    <?php for($i=1;$i<=ceil($rows/10);$i++){?>
	    <li id="page"><a href="#"><?php echo $i;?></a></li>
	    <?php }?>
	    <li onclick="goNextPage()">
	      <a aria-label="Next">
	        <span aria-hidden="true">&raquo;</span>
	      </a>
	    </li>	
	</ul>
</nav>

<script type="text/javascript">
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
	$("td.linkCopy").click(function(){
		var isbn=$(this).attr("dataISBN");
		updateCopyBox(isbn);
	})
	$("button#EditBook").click(function(){
		var tr=$(this).closest('tr');
		var tdData={
			isbn:tr.find('td:eq(0)').text(),
			title:tr.find('td:eq(1)').text(),
			category:tr.find('td:eq(2) #CATEGORY').val(),
			authors:tr.find('td:eq(3)').text(),
			publisher:tr.find('td:eq(4)').text(),
			date:tr.find('td:eq(5)').text()
		}
		initInput();
		$('.sub_author').remove();
		$(".box").show(800);
		$("div.box #ModalTitle").html("Edit of <strong>"+tdData['title']+"</strong>");
		$("div.box #subModalTitle").text(tdData['isbn']);
		$("#book-new-isbn,#book-new-copy").closest('tr').hide();
		$("div.box #saveBook").hide();
		$("div.box #editCheckBook,div.box #uploadImage").show();
		$("div.box #uploadImage").attr("data-isbn",tdData['isbn']);
		$("div.box #book-new-name").val(tdData['title']);
		$("div.box #book-new-year").val(tdData['date']);
		$("div.box #book-new-publisher").val(tdData['publisher']);
		var classId=tdData['category'].split(',');
		$("div.box #book-new-classification").val(classId[0]);
		classificationChange();
		$("div.box #book-new-subclassification").val(classId[1]);
		subClassChange();
		var authorName=tdData['authors'].split(', ');
		for(var key in authorName){
			if(parseInt(key)) addAuthorInput();
			var id=parseInt(key)+1;
			$("#book-new-authors_"+id).val(authorName[key]);
		}
	})
</script>