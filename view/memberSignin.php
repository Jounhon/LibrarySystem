<div class="modal" id="SignInModal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="ModalTitle">Sign In</h4>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="recipient-name" class="control-label">Account:</label>
                <input type="text" class="form-control" id="user-account">
                <label for="message-text" class="control-label">Password:</label>
                <input type="password" class="form-control" id="user-pw">
                <div class="form-inline" style="margin-top:5%;">
                  <input type="text" class="form-control" id="user-code" style="width:60%;">
                  <img src="<?php echo $_SESSION['captcha']['image_src'];?>" alt="CAPTCHA code" width="80" height="40">
                  <!-- <button type="button" class="btn btn-xs glyphicon glyphicon-refresh" id="reCode"></button> -->
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="loginBtn()">Sign In</button>
          </div>
        </div>
      </div>
  </div>
  <script type="text/javascript">
    var loginBtn = function(){
      var formData={
        'account':$("#user-account").val(),
        'pw':$("#user-pw").val()
      }
      if($("#user-code").val()=="<?php echo $_SESSION['captcha']['code'];?>"){
        $.ajax({
         type:"POST",
         url:"../control/login.php",
         data:"account="+formData['account']+"&pw="+formData['pw'],
         dataType:'json',
         success:function(data){
           if(data["status"]=="success"){
             $("#loginLI,#signupLI").hide();
             $("#logoutLI").show();
             $("#SignInModal").modal('hide');
             if(data["authority"]=='3') window.open("http://localhost:8888?ma=y", "_blank");
             window.location.href="http://localhost:8888";
           }
           else alert("Account or Password is Wrong!");
         }
        }).done(function(data){
         console.log(data);
        });
      }else alert("Code is Wrong!");
    }
  </script>