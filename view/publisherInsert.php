<?php
	require_once '../control/conn.php';
?>
<div>
	<form>
	  <h1 id="title">Add New Publisher</h1><br>
	  <table class="table">
	  	<tr>
	  		<td><label for="recipient-name" class="control-label">Name</label></td>
	  		<td><input type="text" class="form-control" id="new-publisher-name"></td>
	  	</tr>
	  	<tr>
	  		<td><label for="message-text" class="control-label">Address</label></td>
	  		<td><input type="text" class="form-control" id="new-publisher-addr"></td>
	  	</tr>
	  </table>
	  <button type="button" class="btn btn-primary btn-lg" id="addBtn">Add</button>
	</form>
</div>
<script type="text/javascript">
	$("#addBtn").click(function(){
		var formData={
			'name':$("#new-publisher-name").val(),
			'addr':$("#new-publisher-addr").val()
		}
		if(formData['name']!=''){
			$.ajax({
				type:"POST",
				url:"../control/insertPublisher.php",
				dataType:'text',
				data:"name="+formData['name']+"&addr="+formData['addr'],
				success:function(data){
					$("#new-publisher-name,#new-publisher-addr").val('');
					$.ajax({
						type:"POST",
						url:'../view/publisherList.php',
						data:'',
						success:function(data){
							$("#publisherList").html(data);
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