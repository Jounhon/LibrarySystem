<?php
	require_once '../control/conn.php';
?>
<div>
	<form>
	  <h1 id="title">Add New Author</h1><br>
	  <table class="table">
	  	<tr>
	  		<td><label for="recipient-name" class="control-label">Name</label></td>
	  		<td><input type="text" class="form-control" id="new-author-name"></td>
	  	</tr>
	  	<tr>
	  		<td><label for="message-text" class="control-label">Orgination</label></td>
	  		<td><input type="text" class="form-control" id="new-author-org"></td>
	  	</tr>
	  </table>
	  <button type="button" class="btn btn-primary btn-lg" id="addBtn">Add</button>
	</form>
</div>
<script type="text/javascript">
	$("#addBtn").click(function(){
		var formData={
			'name':$("#new-author-name").val(),
			'org':$("#new-author-org").val()
		}
		if(formData['name']!=''){
			$.ajax({
				type:"POST",
				url:"../control/insertAuthor.php",
				dataType:'text',
				data:"name="+formData['name']+"&org="+formData['org'],
				success:function(data){
					$("#new-author-name,#new-author-org").val('');
					$.ajax({
						type:"POST",
						url:'../view/authorList.php',
						data:'',
						success:function(data){
							$("#authorList").html(data);
							$('#AddModal').modal('hide');
						}
					});
				}
			}).done(function(data){
				console.log(data);
			});
		}
	})
</script>