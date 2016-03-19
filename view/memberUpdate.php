<?php
	session_start();
	if(!isset($_SESSION['userAuthority'])) echo "<script>window.location='http://localhost'</script>";
	require_once '../control/conn.php';
	$query=mysqli_query($link, 'SELECT * FROM `member` WHERE `account`="'.$_SESSION['userAccount'].'"');
	$fetch=mysqli_fetch_assoc($query);
?>
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Edit Member Infomation</h3>
  </div>
  <div class="panel-body">
    <table class="table">
      	<tr>
          <td class="col-md-3"><label for="recipient-name" class="control-label">Account:</label></td>
          <td class="col-md-7"><?php echo $fetch['account']?></td>
        </tr>
        <tr>
          <td><label for="recipient-name" class="control-label">Name:</label></td>
          <td><div><input type="text" class="form-control" id="user-name" value="<?php echo $fetch['name']?>"></div></td>
        </tr>
        <tr>
          <td><label for="message-text" class="control-label">New Password:</label></td>
          <td><div><input type="password" class="form-control" id="user-npw"></div></td>
        </tr>
        <tr>
          <td><label for="recipient-name" class="control-label">Email:</label></td>
          <td><div><input type="text" class="form-control" id="user-email" value="<?php echo $fetch['email']?>"></div></td>
        </tr>
         <tr>
          <td><label for="recipient-name" class="control-label">Address:</label></td>
          <td><div><input type="text" class="form-control" id="user-addr" value="<?php echo $fetch['address']?>"></div></td>
        </tr>
        <tr>
          <td colspan="2">
            <div class="form-inline" id="footer">
	            <button type="button" class="btn btn-primary pull-right" disabled="true" id="save">Save</button>
	            <input type="password" class="form-control pull-right" id="user-pw" placeholder="Enter Your Password" style="width:30%; margin-right:10px;">
            </div>
          </td>
        </tr>
    </table>
  </div>
</div>
<script type="text/javascript">
	$("input#user-email").inputmask("email",{
        'onincomplete':function(){
          $(this).parent().find('span').remove();
          $(this).parent().removeAttr('class');
          $(this).parent().attr('class','has-warning has-feedback').append($('<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(warning)</span>'));
        },
        'oncomplete':function(){
          $(this).parent().find('span').remove();
          $(this).parent().removeAttr('class');
          $(this).parent().attr('class','has-success has-feedback').append($('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span><span id="inputSuccess2Status" class="sr-only">(success)</span>'))
        }
    });
    $("input#user-pw").keyup(function(){
		if($(this).val()!=''){
			$.ajax({
				type:"POST",
				url:'../control/checkPassword.php',
				data:'account='+<?php echo $_SESSION['userAccount']?>+"&pw="+$(this).val(),
				success:function(data){
					if(data=='success'){
						$("#save").removeAttr("disabled");
					}
					else if(data=='error'){
						$("#save").attr("disabled","true");
					}
				}
			});
		}
	})
    $("input#user-name").blur(function(){
		$(this).parent().find('span').remove();
        $(this).parent().removeAttr('class');
		if($(this).val()=='') $(this).parent().attr('class','has-warning has-feedback').append($('<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(warning)</span>'));
		else $(this).parent().attr('class','has-success has-feedback').append($('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span><span id="inputSuccess2Status" class="sr-only">(success)</span>'))
	})
	$("button#save").click(function(){
		if($("input#user-name").val()==''){
			$("div#footer").find('div.alert').remove();
			$("div#footer").append($('<div class="alert alert-warning pull-left" role="alert" style="margin-top:10px;">Name Can\'t Be Empty !!</div>'));
			return false;
		}
		var emailError=false;
		$("input#user-email").parent().parent().find('.has-warning').each(function(){
			emailError=true;
		})
		if(emailError){
			$("div#footer").find('div.alert').remove();
			$("div#footer").append($('<div class="alert alert-warning pull-left" role="alert" style="margin-top:10px;">Email is Wrong !!</div>'));
			return false;
		}
		$("div#footer").find('div.alert').remove();
		var formData={
			'name':$("input#user-name").val(),
			'email':$('input#user-email').val(),
			'npw':$("input#user-npw").val(),
			'addr':$("input#user-addr").val(),
			'account':<?php echo $_SESSION['userAccount']?>
		};
		if(formData['npw']=='') formData['npw']=$("input#user-pw").val();

		$.ajax({
			type:"POST",
			url:"../control/updateMember.php",
			data:{data:formData},
			success:function(data){
				changeView('MemberInfo');
			}
		});
	})
</script>