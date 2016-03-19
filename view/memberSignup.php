<div class="modal" id="SignUpModal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="ModalTitle">Sign Up</h4>
          </div>
          <div class="modal-body">
            <form>
              <table class="table">
                <tr>
                  <td class="col-md-3"><label for="recipient-name" class="control-label">Name:</label></td>
                  <td class="col-md-7"><div><input type="text" class="form-control" id="new-user-name"></div></td>
                </tr>
                <tr>
                  <td><label for="recipient-name" class="control-label">Account:</label></td>
                  <td><div><input type="text" class="form-control" id="new-user-account" placeholder="Enter your StudentID"></div></td>
                </tr>
                <tr>
                  <td><label for="recipient-name" class="control-label">Email:</label></td>
                  <td><div><input type="text" class="form-control" id="new-user-email"></div></td>
                </tr>
                <tr>
                  <td><label for="message-text" class="control-label">Password:</label></td>
                  <td><div><input type="password" class="form-control" id="new-user-pw"></div></td>
                </tr>
                <tr>
                  <td><label for="message-text" class="control-label">Comfirm Password:</label></td>
                  <td><div><input type="password" class="form-control" id="new-user-cfpw"></div></td>
                </tr>
              </table>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="signUpcheck()">Sign Up</button>
          </div>
        </div>
      </div>
  </div>
  <script type="text/javascript">
    $("#new-user-email").inputmask("email",{
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
      $("#new-user-account").inputmask("999999999",{
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

      $("#SignUpModal input").blur(function(){
        $(this).parent().find('span').remove();
        $(this).parent().removeAttr('class');
        if($(this).val()==''){
          $(this).parent().attr('class','has-warning has-feedback').append($('<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(warning)</span>'));
        }
        else $(this).parent().attr('class','has-success has-feedback').append($('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span><span id="inputSuccess2Status" class="sr-only">(success)</span>'))
        if(this.id=='new-user-cfpw'&&$(this).val()!=''){
          $(this).parent().find('span').remove();
          $(this).parent().removeAttr('class');
          if($(this).val()!=$("#SignUpModal input#new-user-pw").val()){
            $(this).parent().attr('class','has-error has-feedback').append($('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(danger)</span>'));
          }
          else $(this).parent().attr('class','has-success has-feedback').append($('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span><span id="inputSuccess2Status" class="sr-only">(success)</span>'))
        }
      })
      var signUpcheck=function(){
        var error=0;
        var same=true;
        $('#SignUpModal .modal-footer').find('div.alert').remove();
        $("div.has-warning,div.has-error").find('span').remove();
        $("div.has-warning,div.has-error").removeAttr('class');
        var cfpw=$("#SignUpModal input#new-user-cfpw");
        var pw=$("#SignUpModal input#new-user-pw");
        if(cfpw.val()!=''&&pw.val()!=''&&cfpw.val()!=pw.val()){
          same=false;
          cfpw.parent().attr('class','has-error has-feedback').append($('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(danger)</span>'));
          $('#SignUpModal .modal-footer').append($('<div class="alert alert-danger pull-left" role="alert" style="margin-top:10px;">Password is not Correct !!</div>'));
        }
        if(same){
          $("#SignUpModal input").each(function(){
            if($(this).val()==''){
              error++;
              $(this).parent().attr('class','has-warning has-feedback').append($('<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(warning)</span>'));
            }
          })
          $("div.has-warning,div.has-dange").each(function(){
            error++;
          });
          if(error>0) $('#SignUpModal .modal-footer').append($('<div class="alert alert-warning pull-left" role="alert" style="margin-top:10px;">Some Field is Empty !!</div>'));
          else signup();
        }   
      }

      var signup = function(){
        var formData={
          'name':$("#SignUpModal input#new-user-name").val(),
          'email':$("#SignUpModal input#new-user-email").val(),
          'account':$("#SignUpModal input#new-user-account").val(),
          'password':$("#SignUpModal input#new-user-pw").val()
        }
        $('#SignUpModal .modal-footer').append($('<div class="alert alert-success pull-left" role="alert" style="margin-top:10px;"><span class="glyphicon glyphicon-refresh spinning"></span><strong> Waiting...</strong></div>'));
        $.ajax({
          type:"POST",
          url:"../control/insertMember.php",
          data:{data:formData},
          success:function(data){
            console.log(data);
            if(data=="success"){
              $("#loginLI,#signupLI").hide();
              $("#logoutLI").show();
              $("#SignUpModal").modal('hide');
              window.location="http://localhost";
            }
            else{
              $('#SignUpModal .modal-footer').find('div.alert').remove();
              if(data=='errorOfAccount'){
                $("#SignUpModal input#new-user-account").parent().find('span').remove();
                $("#SignUpModal input#new-user-account").parent().removeAttr('class');
                $("#SignUpModal input#new-user-account").parent().attr('class','has-error has-feedback').append($('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(danger)</span>'));
                $('#SignUpModal .modal-footer').append($('<div class="alert alert-danger pull-left" role="alert" style="margin-top:10px;">This account has been used !!</div>'));
              }else $('#SignUpModal .modal-footer').append($('<div class="alert alert-danger pull-left" role="alert" style="margin-top:10px;">Oops! System is shut down !! We\'ll fix it ASAP.</div>'));
            }
          }
        })
      }
  </script>