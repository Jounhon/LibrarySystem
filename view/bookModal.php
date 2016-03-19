<!-- box call author/classification insert modal-->
<div class="modal" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Modal title</h4>
        </div>
        <div class="modal-body" id="Body_Box"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
  </div>
</div>
<!-- list call delete modal-->
<div class="modal" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="ModalTitle"></h3>
        <h5 class="modal-title" id="ModalSubTitle"></h5><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="deleteBook">Delete</button>
      </div>
    </div>
  </div>
</div>
<!-- list call add copy box modal-->
<div class="modal" id="AddCopyModal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="ModalTitle"></h4>
      </div>
        <div class="modal-body">
          <div class="form-inline">
            <label class="control-label">Add</label>
            <input type="number" value="1" min="1" class="form-control" id="book-copy" style="width:30% !important;">
            <label class="control-label">Copies</label>
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="AddCopy">Add</button>
      </div>
    </div>
  </div>
</div>

<!-- list call record box Modal-->
<div class="modal" id="recordModal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
          <div style="max-height:80%;overflow:auto;">
            <table class="table table-hover" id="recordTable" >
              <thead class="table table-striped">
                <tr>
                    <th data-sort="int"><a>Copy no.</a></th>
                    <th data-sort="string"><a>Account</a></th>
                    <th data-sort="string"><a>Check in</a></th>
                    <th data-sort="string"><a>Check out</a></th>
                    <th data-sort="string"><a>Hold</a></th>
                  </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- upload image box Modal-->
<div class="modal" id="uploadImageModal" tabindex="-1" role="dialog" aria-labelledby="ModalTitle">
  <div class="modal-dialog modal-lg" role="document" style="height:40%;">
    <div class="modal-content">
        <div class="modal-header" align="right">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <div class="modal-body">
          <input id="images" name="images[]" type="file" multiple class="file-loading">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$('#AddModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) ;
  var select = button.data('select');
  var modal = $(this);
  modal.find('.modal-title').text('Add New ' + select);
  var url='../view/';
  if(select=="author"){
    url+=select+"Insert.php";
  }
  else if(select=="publisher"){
    url+=select+"Insert.php";
  }
  $.ajax({
  type:"POST",
  url:url,
  data:'',
  success:function(data){
    modal.find('.modal-body').html(data);
    $("#title").hide();
  }
  });
})
$('#DeleteModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var formData={
    'name':button.data('name'),
    'isbn':button.data('isbn')
  }
  var modal = $(this);
  modal.find('#ModalTitle').html('確定要刪除<br><strong>'+formData['name']+'</strong>?');
  modal.find('#ModalSubTitle').text('ISBN :'+formData['isbn']);
  modal.find('#deleteBook').click(function(){
    $.ajax({
    type:"POST",
    url:"../control/deleteBook.php",
    data:"isbn="+formData['isbn'],
    success:function(data){
      $('#DeleteModal').modal('hide');
      $.ajax({
        type:"POST",
        url:"../view/bookList.php",
        data:'',
        success:function(data){
          $("#copyBox" ).slideUp("slow").hide("slow",function(){
            $( "#Booklist" ).animate({
                marginLeft: "0%",
              },1500);
          });
          $("#Booklist").html(data);
        }
      })
    }
  });
  });
});

$('#AddCopyModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var formData={
    'name':button.data('name'),
    'isbn':button.data('isbn')
  }
  var modal = $(this);
  modal.find('#ModalTitle').html('Add Copy of<br><strong>'+formData['name']+'</strong>');
  modal.find('#AddCopy').click(function(){  
    $.ajax({
      type:"POST",
      url:"../control/addBookCopy.php",
      data:"isbn="+formData['isbn']+"&copy="+modal.find('#book-copy').val(),
      success:function(data){
        console.log(data);
        $('#AddCopyModal').modal('hide');
        $.ajax({
          type:"POST",
          url:"../view/bookList.php",
          data:'',
          success:function(data){
            updateCopyBox(formData['isbn']);
            $("#Booklist").html(data);
            return false;
          }
        })
      }
    });
  });
});

$('#recordModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) ;
  var isbn = button.data('isbn');
  var modal = $(this);
  $('table#recordTable').stupidtable();
  $.ajax({
    type:"POST",
    url:'../control/getLog.php',
    data:'action=booklog&isbn='+isbn,
    dataType:'json',
    success:function(data){
      console.log(data);
      $('table#recordTable tbody tr').remove();
      if(data.length==0){
        $('table#recordTable tbody').append($('<tr style="text-align:center;" class="danger"><td colspan="5">No Record!</td></tr>'))
      }
      else{
        for(var key in data){
          $('table#recordTable tbody').append($('<tr><td>'+data[key]['copy']+'</td><td>'+data[key]['account']+'</td><td>'+(data[key]['in']==null?'':data[key]['in'])+'</td><td>'+(data[key]['out']==null?'':data[key]['out'])+'</td><td>'+(data[key]['hold']==null?'':data[key]['hold'])+'</td></tr>'))
        }
      }
    }
  });
})
$('#uploadImageModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) ;
  var isbn = button.data('isbn');
  var modal = $(this);
  $("#images").fileinput({
      uploadUrl: "../control/updateImage.php", // server upload action
      uploadAsync: true,
      uploadExtraData: function() {
          return {
              isbn: isbn
          };
      },
      maxFileCount: 1,
      initialPreview: [
        '<img src="/images/No_image.jpg" class="file-preview-image" alt="No_image" title="No_image">'
      ],
      overwriteInitial: true,
      allowedFileTypes: ["image"],
  });
})

</script>
