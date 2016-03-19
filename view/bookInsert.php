<div class="modal-content box" style="display:none;width:80%;margin-left:10%;">
  <div class="modal-header">
    <button type="button" class="close" aria-label="Close" onclick="closeBox()"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title" id="ModalTitle"></h3>
    <h5 class="modal-title" id="subModalTitle"></h5>
  </div>
  <div class="modal-body">
    <form>
      <table class="table">
      	<tr>
      		<td class="col-md-3"><label for="recipient-name" class="control-label">Title</label></td>
      		<td class="col-md-7">
      			<div>
      				<input type="text" class="form-control" id="book-new-name" data-select="Title">
      			</div>
      		</td>
      	</tr>
      	<tr>
      		<td><label for="recipient-name" class="control-label">ISBN</label></td>
      		<td>
      			<div>
      				<input type="text" class="form-control" id="book-new-isbn" placeholder="___-___-___-___-_" data-select="ISBN">
      			</div>
      		</td>
      	</tr>
      	<tr>
      		<td><label for="recipient-name" class="control-label">Category</label></td>
      		<td >
      			<div class="form-inline">
        			<select class="form-control" style="width:40%;" id="book-new-classification">
        			</select>
        			<select class="form-control" style="width:50%;" id="book-new-subclassification">
        			</select>
      			</div>
      			<div class="alert alert-info" role="alert" id="book-new-subdetail" style="margin-top:10px;display:block;"></div>
      		</td>
      	</tr>
      	<tr>
      		<td><label for="recipient-name" class="control-label">Author(s)</label></td>
      		<td id="td_author">
      			<div class="dropdown form-inline box_1">
        			<input type="text" class="form-control dropdown-toggle author" id="book-new-authors_1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-select="author">
        			<button type="button" class="btn btn-primary btn-circle" onclick="addAuthorInput()"><i class="glyphicon glyphicon-plus"></i></button>
        			<ul class="dropdown-menu" id="author-dropdown_1" aria-labelledby="book-new-authors"></ul>
        			<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#AddModal" data-select="author"><i class="glyphicon glyphicon-plus-sign"></i> Add New Author</button>
			</div>
      		</td>
      	</tr>
      	<tr>
      		<td><label for="recipient-name" class="control-label">Publisher</label></td>
      		<td>
      			<div class="dropdown form-inline">
        			<input type="text" class="form-control dropdown-toggle " id="book-new-publisher" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-select="publisher">
        			<ul class="dropdown-menu" id="publisher-dropdown" aria-labelledby="book-new-publisher"></ul>
        			<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#AddModal" data-select="publisher"><i class="glyphicon glyphicon-plus-sign"></i> Add New Publisher</button>
			</div>
      		</td>
      	</tr>
      	<tr>
      		<td><label for="recipient-name" class="control-label">Year of publication</label></td>
      		<td>
      			<div>
      				<input type="date" class="form-control" id="book-new-year" data-select="Publisher Date">
      			</div>
      		</td>
      	</tr>
      	<tr>
      		<td><label for="recipient-name" class="control-label">Copy Number</label></td>
      		<td>
      			<div>
      				<input type="number" value="1" min="1" class="form-control" id="book-new-copy" data-select="copy">
      			</div>
      		</td>
      	</tr>
      </table>
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" onclick="closeBox()">Close</button>
    <button type="button" class="btn btn-primary" id="saveBook">Add</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" id="uploadImage" data-target="#uploadImageModal" style="display:none;"><i class="glyphicon glyphicon-cloud-upload"></i> Upload Image</button>
    <button type="button" class="btn btn-primary" id="editCheckBook" style="display:none;">Save</button>
  </div>
</div>
