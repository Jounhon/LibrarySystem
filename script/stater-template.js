// JavaScript Document
var changeView=function(menu){
	var view_url;
	switch(menu){
		case 'ManageMember':
			view_url='../view/ManageMember.php';
		break;
		case 'ManageAuthor':
			view_url='../view/ManageAuthor.php';
		break;
		case 'ManagePublisher':
			view_url='../view/ManagePublisher.php';
		break;
		case 'ManageBook':
			view_url='../view/ManageBook.php';
		break;
		case 'MemberInfo':
			view_url='../view/ManageAccount.php?action=info';
		break;
		case 'MemberMessage':
			view_url='../view/ManageAccount.php?action=message';
		break;
		case 'HistorySelf':
			view_url='../view/ManageAccount.php?action=log';
		break;
		case 'CheckIO':
			view_url='../view/CheckIO.php';
		break;
		case 'mission':
			view_url='../view/autoMission.php';
		break;
	}
	$.ajax({
		type:"POST",
		url:view_url,
		data:'',
		success:function(data){
			$("#main").html(data);
		}
	});
}

var logout =function(){
	$.ajax({
		type:"POST",
		url:"../control/logout.php",
		data:'',
		success:function(data){
			if(data=="success") {
                $("#loginLI,#signupLI").show();
                $("#logoutLI,#messageLI").hide()
            }
			window.location.href="http://localhost:8888";
		}
	}).done(function(data){
		console.log(data);
	});
}