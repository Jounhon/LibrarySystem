<?php 
  session_start(); 
  // if (isset($_SESSION['LAST_ACTIVITY']) && (time() -   $_SESSION['LAST_ACTIVITY'] > 300)) {
  //  header("Location:http://localhost/control/logout.php");
  // }  
  // $_SESSION['LAST_ACTIVITY'] = time(); // the start of the session.
  dirname(__FILE__);
  require_once(dirname(__FILE__).'/control/conn.php');
  include("./simple-php-captcha-master/simple-php-captcha.php");
  $_SESSION['captcha'] = simple_php_captcha( array(
      'min_length' => 5,
      'max_length' => 5,
      //'backgrounds' => array(image.png', ...),
      //'fonts' => array('font.ttf', ...),
      //'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
      'min_font_size' => 28,
      'max_font_size' => 28,
      'color' => '#666',
      'angle_min' => 0,
      'angle_max' => 10,
      'shadow' => true,
      'shadow_color' => '#fff',
      'shadow_offset_x' => -1,
      'shadow_offset_y' => 1
  ));
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Library Syetem</title>
	<link rel="stylesheet" type="text/css" href="bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="bootstrap-toggle-master/css/bootstrap-toggle.css">
  <link rel="stylesheet" type="text/css" href="bootstrap-fileinput-master/css/fileinput.min.css">
  <link rel="stylesheet" type="text/css" href="jquery.tzineClock/jquery.tzineClock.css">
  <link rel="stylesheet" type="text/css" href="FlipClock-master/compiled/flipclock.css">
  <link rel="stylesheet" type="text/css" href="css/starter-template.css">
  <link rel="stylesheet" type="text/css" href="bootstrap-star-ranting/css/star-rating.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap-vertical-tabs/bootstrap.vertical-tabs.min.css">
  <script type="text/javascript" src="../script/jquery-2.1.4.min.js"></script>
  <script src="../script/jquery.filtertable.js"></script>
  <script src="../script/jquery.sieve.min.js"></script>
  <script src="../script/jquery.inputmask.bundle.js"></script>
  <script src="../script/stupidtable.js"></script>
  <script src="../jquery.tzineClock/jquery.tzineClock.js"></script>
  <script src="../FlipClock-master/compiled/flipclock.min.js"></script>
  <script src="../script/stater-template.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://localhost:8888">Library System</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          <?php
          	if(isset($_SESSION['userAuthority'])&&$_SESSION['userAuthority']!='1'){
          ?>
            <li><a href="javascript: changeView('CheckIO')">Check In/Out <span class="glyphicon glyphicon-book" aria-hidden="true"></span></a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">  Management <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>  <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="javascript: changeView('ManageAuthor')">Manage Author</a></li>
                <li><a href="javascript: changeView('ManagePublisher')">Manage Publisher</a></li>
                <li><a href="javascript: changeView('ManageBook')">Manage Book</a></li>
                <?php if($_SESSION['userAuthority']=='3'){ ?>
                <li role="separator" class="divider"></li>
                <li><a href="javascript: changeView('ManageMember')">Manage Member</a></li>
                <?php }?>
              </ul>
            </li>
            <?php if($_SESSION['userAuthority']=='3'){ ?>
            <li><a href="?ma=y" target="blank">Auto System <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span></a></li>
          <?php }}?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
	          <li id="loginLI" style="display:none;">
              <button type="button" class="btn btn-info navbar-btn" data-toggle="modal" data-target="#SignInModal">Sign In</button>
            </li>
            <li id="signupLI" style="display:none;">
              <button type="button" class="btn btn-info navbar-btn" style="margin-left:20px !important;" data-toggle="modal" data-target="#SignUpModal">Sign Up</button>
            </li>
            <li id="messageLI" class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> <span class="badge">0</span></a>
              <ul class="dropdown-menu"></ul>
            </li>
            <li id="logoutLI" style="display:none;" class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><strong>  <?php echo $_SESSION['userName'];?> </strong><span class="glyphicon glyphicon-user" aria-hidden="true"></span>  <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="javascript: changeView('MemberInfo')">Account Setting</a></li>
                <li><a href="javascript: changeView('HistorySelf')">Check In/Out Log</a></li>
                <li role="separator" class="divider"></li>
                <li align="center"><button class="glyphicon glyphicon-off btn btn-danger btn-sm" onclick="logout()"><strong> Log Out</strong></button></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div id="main" class="container" align="center">
      <div class="starter-template">
        <?php   if(isset($_GET['ma'])&&$_GET['ma']=='y'){ echo "<script>changeView('mission')</script>";}
                else { include(dirname(__FILE__).'/view/indexView.php'); } ?>
      </div>
    </div>

    <footer class="footer" style="width:100%; text-align:center; padding-bottom:20px;">
      <hr/>
      <p>CopyRight <span class="glyphicon glyphicon-copyright-mark"></span> By Neo (2015)</p>
    </footer>
<?php
  include (dirname(__FILE__).'/view/memberSignin.php');
  include (dirname(__FILE__).'/view/memberSignup.php');
?>
</body>
</html>

<script type="text/javascript">
  var messageRefresh=function(){
    <?php if(isset($_SESSION['userAccount'])&&$_SESSION['userAccount']){ ?>
    var id = <?php echo $_SESSION['userAccount']; ?>;
    <?php }?>
    $.ajax({
      type:"POST",
      url:"../control/getMessage.php",
      data:"action=menu&account="+id,
      dataType:'json',
      success:function(data){
        console.log(data);
        $("#messageLI").find('li').remove();
        for(var key in data){
          if(key=='0'){
            $("#messageLI a span.badge").html(data[key]['count']);
          }
          else{
            if(data[key]['read']==true){
              $("#messageLI").find('ul').append('<li><a href="javascript: changeView(\'MemberMessage\')" style="color:#ccc;">'+data[key]['title']+'<br>'+data[key]['content'].substring(0, 15)+'...>></a></li>');
            }else{
              $("#messageLI").find('ul').append('<li><a href="javascript: changeView(\'MemberMessage\')"><b>'+data[key]['title']+'<br>'+data[key]['content'].substring(0, 15)+'...>></b></a></li>');
            }
          }
        }
        if(data.length>1) $("#messageLI").find('ul').append('<li role="separator" class="divider"></li><li><a href="javascript: changeView(\'MemberMessage\')">See More ...</a></li>');
      }
    });
  }
	$(document).ready(function() {
		var id='';
		<?php if(isset($_SESSION['userAccount'])&&$_SESSION['userAccount']){ ?>
	    var id = <?php echo $_SESSION['userAccount']; ?>;
	    <?php }?>
	    if(id!=''){
	    	$("#loginLI,#signupLI").hide();
	    	$("#logoutLI,#messageLI").show();
        messageRefresh();
	    }else{
	    	$("#loginLI,#signupLI").show();
	    	$("#logoutLI,#messageLI").hide();
	    }
	});
</script>
<script src="bootstrap/dist/js/bootstrap.min.js"></script>
