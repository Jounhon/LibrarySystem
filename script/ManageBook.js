//****  輸入作者或出版社及時搜尋資料庫顯示list於input下
var setSearch=function(){
	$(".author").keyup(function(){
	  	var curId=this.id.split("_")[1];
	  	if($(this).val()!=''){
	  		$.ajax({
				type:"POST",
				url:'../control/getAuthors.php',
				data:'key='+$(this).val()+"&action=search",
				dataType:'json',
				success:function(data){
					$("#author-dropdown_"+curId+" li").remove();
					for(var key in data){
						if(data[key]['name']=='查無此作者')
							$("#author-dropdown_"+curId).append($("<li class='disabled'></li>").append($("<a></a>").text(data[key]['name'])));
						else
							$("#author-dropdown_"+curId).append($("<li></li>").append($("<a></a>").text(data[key]['name'])));
					}
				}
			});
  		}
    });
    $("#book-new-publisher").keyup(function(){
	  	if($(this).val()!=''){
	  		$.ajax({
				type:"POST",
				url:'../control/getPublisher.php',
				data:'key='+$(this).val(),
				dataType:'json',
				success:function(data){
					$("#publisher-dropdown li").remove();
					for(var key in data){
						if(data[key]['name']=='查無此作者')
							$("#publisher-dropdown").append($("<li class='disabled'></li>").append($("<a></a>").text(data[key]['name'])));
						else
							$("#publisher-dropdown").append($("<li></li>").append($("<a></a>").text(data[key]['name'])));
					}
				}
			});
  		}
    });
	$("#td_author ul.dropdown-menu").on('click','li a',function(){
		if($(this).text()=='查無此作者') return;
	  	var curId=$(this).parent().parent().attr('id').split("_")[1];
	  	$("#book-new-authors_"+curId).val($(this).text());
	})
	$("#publisher-dropdown").on('click','li a',function(){
		if($(this).text()=='查無此作者') return;
	  	$("#book-new-publisher").val($(this).text());
	})
	$(".removeInput").click(function(){
		$(this).parent().remove();
		var curId=1;
		$('#td_author div.dropdown').each(function(){
			$(this).removeAttr('class').attr('class','dropdown form-inline box_'+curId);
			$(this).find('.author').attr('id','book-new-authors_'+curId);
			$(this).find('ul').attr('id','author-dropdown_'+curId);
			curId++;
		})
	})
}

//**** 初始化分類選單
var initCategory =function(){
	$.ajax({
		type:"POST",
		url:'../control/getCategory.php',
		data:'',
		dataType:'json',
		success:function(data){
			for(var key in data){
				$("div.box #book-new-classification").append($("<option></option>").attr("value", data[key]['id']).text(data[key]['name']));
			}
			$.ajax({
				type:"POST",
				url:'../control/getSubCategory.php',
				data:'id='+$("div.box #book-new-classification option:selected").val(),
				dataType:'json',
				success:function(data){
					for(var key in data){
						$("div.box #book-new-subclassification").append($("<option></option>").attr("value", data[key]['id']).text(data[key]['name']));
					}
					$.ajax({
						type:"POST",
						url:'../control/getSubDetail.php',
						data:'id='+$("div.box #book-new-subclassification option:selected").val(),
						dataType:'text',
						success:function(data){
							$("div.box #book-new-subdetail").text(data);
						}
					});
				}
			});
		}
	});
}

//**** 主分類選單變動時
var classificationChange=function(){
	$("#book-new-subclassification option").remove();
  	$.ajax({
		type:"POST",
		url:'../control/getSubCategory.php',
		data:'id='+$("#book-new-classification").val(),
		dataType:'json',
		success:function(data){
			for(var key in data){
				$("#book-new-subclassification").append($("<option></option>").attr("value", data[key]['id']).text(data[key]['name']));
			}
			$.ajax({
				type:"POST",
				url:'../control/getSubDetail.php',
				data:'id='+$("#book-new-subclassification option:selected").val(),
				dataType:'text',
				success:function(data){
					$("#book-new-subdetail").text(data);
				}
			});
		}
	});
}

//****  子分類選單變動時
var subClassChange= function(){
	$.ajax({
		type:"POST",
		url:'../control/getSubDetail.php',
		data:'id='+$("#book-new-subclassification").val(),
		dataType:'text',
		success:function(data){
			$("#book-new-subdetail").text(data);
		}
	});
}

//****  增加作者欄位
var addAuthorInput=function(){
	var count_author=1;
	$(".author").each(function(){
		count_author++;
	})
	$("#td_author").append($('<div class="sub_author dropdown form-inline box_'+count_author+'"><input type="text" class="form-control dropdown-toggle author" id="book-new-authors_'+count_author+'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-select="author"><button type="button" class="btn btn-danger btn-circle removeInput" ><i class="glyphicon glyphicon-minus"></i></button><ul class="dropdown-menu" id="author-dropdown_'+count_author+'" aria-labelledby="book-new-authors"></ul></div>'));
	setSearch();
}

//**** 初始化BOX 欄位 & 清空警告
var initInput=function(){
	$('.alert-danger,.alert-warning,.alert-success,.sub_author').remove();
	$("div.box #book-new-name,div.box #book-new-isbn,div.box #book-new-year").parent().removeAttr('class').find('span').remove();
}

//**** 關閉BOX
var closeBox=function(){
	$('.box').hide(800);
}

//**** 顯示BOX
$('#addBook').click(function(){
	initInput();
	$(".box").show(800);
	$("div.box #ModalTitle").html("Add New Book");
	$("div.box #subModalTitle").text('');
	$("div.box input").each(function(){
		$(this).val('');
		$(this).closest('tr').show();
	})
});

//**** BOX 分類選單變動時
$("#book-new-classification").change(function(){
  	classificationChange();
});

$("#book-new-subclassification").change(function(){
	subClassChange();
});

//****  檢查 insert box 是否有欄位不符規定 或 資料不符資料庫的
$("#saveBook").click(function(){
	var error=0,notExist=false;
	$('.modal-footer').find('div.alert').remove();
	$("div.has-warning,div.has-dange").each(function(){
		error++;
	});
	$("div.box input").each(function(){
		var select=$(this).data('select');
		if(select=='publisher'){
			if($(this).val()=='') error++;
			$.ajax({
				type:"POST",
				url:'../control/checkPuclisher.php',
				data:'publisher='+$(this).val(),
				dataType:'text',
				success:function(data){
					if(data=='error'&&!notExist){
						notExist=true;
						$('.modal-footer').append($('<div class="alert alert-danger pull-left" role="alert" style="margin-top:10px;"><strong>Alert!</strong> Author(s) OR Publisher is Not EXIT ! Please Chcek Again !! If Not Exit, You can Add New One.</div>'));
					}
				}
			});
		}
		else if(select=='author'){
			if($(this).val()!=''){
				$.ajax({
					type:"POST",
					url:'../control/checkAuthor.php',
					data:'author='+$(this).val(),
					dataType:'text',
					success:function(data){
						if(data=='error'&&!notExist){
							notExist=true;
							$('.modal-footer').append($('<div class="alert alert-danger pull-left" role="alert" style="margin-top:10px;"><strong>Alert!</strong> Author(s) OR Publisher is Not EXIT ! Please Chcek Again !! If Not Exit, You can Add New One.</div>'));
						}
					}
				});
			}
		}else{
			if($(this).val()=='') error++;
		}
	})
	if(error>0) $('.modal-footer').append($('<div class="alert alert-warning pull-left" role="alert" style="margin-top:10px;"><strong>Warning!</strong> Some Filed are Still EMPTY!! Or Some Filed are Still ERROR !!! Please Check Again!!</div>'));
	if(error==0 && !notExist) setTimeout(insertBook,1000);
})

//**** 新增書
var insertBook=function(){
	var dataForm=[];
	$("div.box input,div.box select").each(function(){
		dataForm.push({
			selector:this.id.split('-')[2],
			value:$(this).val()
		});
	})
	$('.modal-footer').append($('<div class="alert alert-success pull-left" role="alert" style="margin-top:10px;"><span class="glyphicon glyphicon-refresh spinning"></span><strong> Waiting...</strong></div>'));
	$.ajax({
		type:"POST",
		url:'../control/insertBook.php',
		data:{data:dataForm},
		success:function(data){
			console.log(data);
			if(data=='success'){
				$('.modal-footer').find('.alert').remove(function(){
					closeBox();
					$.ajax({
						type:"POST",
						url:"../view/bookList.php",
						data:'',
						success:function(data){
							$("#Booklist").html(data);
						}
					}).done(function(){
						updateCopyBox(isbn);
					})
				});
				changeView('ManageBook');
			}
		}
	});
}

//****  檢查edit box 是否有欄位不符規定 或是 資料不符資料庫的
$("#editCheckBook").click(function(){
	var error=0,notExist=false;
	$('.modal-footer').find('div.alert').remove();
	$("div.has-warning,div.has-error").each(function(){
		error++;
	});
	$("div.box input").each(function(){
		var select=$(this).data('select');
		switch(select){
			case 'publisher':
				if($(this).val()=='') error++;
				$.ajax({
					type:"POST",
					url:'../control/checkPuclisher.php',
					data:'publisher='+$(this).val(),
					dataType:'text',
					success:function(data){
						if(data=='error'&&!notExist){
							notExist=true;
							$('.modal-footer').append($('<div class="alert alert-danger pull-left" role="alert" style="margin-top:10px;"><strong>Alert!</strong> Author(s) OR Publisher is Not EXIT ! Please Chcek Again !! If Not Exit, You can Add New One.</div>'));
						}
					}
				});
			break;
			case 'author':
				if($(this).val()!=''){
					$.ajax({
						type:"POST",
						url:'../control/checkAuthor.php',
						data:'author='+$(this).val(),
						dataType:'text',
						success:function(data){
							if(data=='error'&&!notExist){
								notExist=true;
								$('.modal-footer').append($('<div class="alert alert-danger pull-left" role="alert" style="margin-top:10px;"><strong>Alert!</strong> Author(s) OR Publisher is Not EXIT ! Please Chcek Again !! If Not Exit, You can Add New One.</div>'));
							}
						}
					});
				}
			break;
			case 'copy':
			case 'Title':
				if($(this).val()=='') error++;
			break;
		}
	})
	if(error>0) $('.modal-footer').append($('<div class="alert alert-warning pull-left" role="alert" style="margin-top:10px;"><strong>Warning!</strong> Some Filed are Still EMPTY!! Or Some Filed are Still ERROR !!! Please Check Again!!</div>'));
	if(error==0 && !notExist) setTimeout(updateBook,1000);
})

//**** 更新書
var updateBook = function(){
	var dataForm=[];
	$("div.box input,div.box select").each(function(){
		dataForm.push({
			selector:this.id.split('-')[2],
			value:$(this).val()
		});
	})
	dataForm[1]['value']=$("div.box #subModalTitle").text();
	$.ajax({
		type:"POST",
		url:'../control/updateBook.php',
		data:{data:dataForm},
		success:function(data){
			console.log(data);
			if(data=='success'){
				closeBox();
				$.ajax({
					type:"POST",
					url:"../view/bookList.php",
					data:'',
					success:function(data){
						$("#Booklist").html(data);
					}
				})
			}
		}
	});
}

//****  檢查BOX是否有空白 或 copy field非正確數字格式
var erorrCount={
	Title:0,
	'Publisher Date':0,
	ISBN:0,
	Publisher:0
};

$("div.box input").blur(function(){
	var select=$(this).data('select');
	switch(select){
		case 'Title': 
		case 'Publisher Date':
			if($(this).val()==''){
				$(this).parent().removeAttr('class').find('span').remove();
				if(erorrCount[select]==0)
					$(this).parent().attr('class','has-warning has-feedback').append($('<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span><span id="inputError2Status" class="sr-only">(warning)</span><div class="alert alert-warning" role="alert" style="margin-top:10px;">'+select+' Can\'t Be Empty!!</div>'))
				erorrCount[select]++;
			}
			else{
				erorrCount[select]=0;
				$(this).parent().removeAttr('class').find('span').remove();
				$(this).parent().find('div.alert').remove();
				$(this).parent().attr('class','has-success has-feedback').append($('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span><span id="inputSuccess2Status" class="sr-only">(success)</span>'))
			}
		break;
		case 'copy':{
			if($(this).val()<=0){
				$(this).val(1);
			}
		}
	}
})

//******************  分頁 & 表格分頁顯示
 var diviseList=function(index){
	var pages=$('.pagination li').size();
	$('.pagination li').removeAttr('class');
	if(index==1) $('.pagination li:eq(0)').attr('class','disabled');
	if(index==pages-2) $('.pagination li:eq('+(pages-1)+')').attr('class','disabled');
	$('.pagination li:eq('+index+')').attr('class','active');
	$('#BookTable tr').hide();
	$('#BookTable tr:eq(0)').show();
	for(var i=1+(index-1)*10;i<=index*10;i++){
		$('#BookTable tr:eq('+i+')').show();
	}
}

//**** 顯示當前頁的資料到表格
$('.pagination li#page').click(function(){
	var index=$(this).index();
	diviseList(index);
});

//**** 分頁上下頁按鈕事件
var goPrePage=function(){
	var name=$('.pagination li:eq(0)').attr('class');
	if(name!='disabled'){
		var index=$('.pagination li#page.active').index();
		diviseList(index-1);
	}
}
var goNextPage=function(){
	var pages=$('.pagination li').size();
	var name=$('.pagination li:eq('+(pages-1)+')').attr('class');
	if(name!='disabled'){
		var index=$('.pagination li#page.active').index();
		diviseList(index+1);
	}
}

//****  copy box 書狀態顏色
var getColor=function(status){
	switch (status) {
		case 'on-shelf':
			return 'default';
			break;
		case 'on-hold':
			return 'success';
			break;
		case 'on-loan':
			return 'danger';
			break;
		case 'on-hold / on-loan':
			return 'warning';
			break;
	}
}
var updateCopyBox=function(isbn){
	$.ajax({
		type:"POST",
		url:"../control/getBookCopy.php",
		data:"isbn="+isbn,
		dataType:'json',
		success:function(data){
			$("#copyBox div.content_box").fadeOut(300,function(){
				$(this).remove();
			});
			for(var key in data){
				$("#copyBox div.outCopybox").append($('<div class="content_box" style="display:none;"><div class="thumbnail"><div class="caption" style="text-align:left;"><span class="pull-right label label-'+getColor(data[key]['status'])+'">'+data[key]['status']+'</span><sapn style="font-size:10px;">Copy No.'+data[key]['copy']+'</span><br><span style="font-size:10px;">Code: '+data[key]['code']+'</sapn>'+(data[key]['status']=='on-shelf'?'<button class="btn btn-danger btn-xs pull-right copyRemove" type="button" dataCode="'+data[key]['code']+'">Remove </button>':'')+'</div></div></div>'));
			}
			$("#copyBox div.content_box").show("slow");
			$('.copyRemove').click(function(){
				if($(this).text()=="You Sure ?"){
					$.ajax({
						type:"POST",
						url:"../control/deleteBook.php",
						data:'action=copyDelete&code='+$(this).attr('dataCode'),
						success:function(data){
							if(data=='success'){
								$.ajax({
									type:"POST",
									url:"../view/bookList.php",
									data:'',
									success:function(data){
										$("#Booklist").html(data);
									}
								}).done(function(){
									updateCopyBox(isbn);
								})
							}
						}
					})
				}
				else $(this).text("You Sure ?");
			})
		}
	}).done(function(){
		if($("#copyBox").css('display')=='none'){
			$("#Booklist").animate({
		    	marginLeft: "40%",
		  	},1500,function(){
	      		$("#copyBox").slideDown("slow").show("slow");
		  	});
		}
	})
}

$("#copyBoxClose").click(function(){
	$("#copyBox" ).slideUp("slow").hide("slow",function(){
		$( "#Booklist" ).animate({
		    marginLeft: "0%",
		  },1500);
	});
})