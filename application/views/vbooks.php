<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Books CRUD</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="row">
			<h1 class="page-header">BookList
				<div class="pull-right"><a href="#" onclick="add_books()" class="btn btn-sm btn-success"><span class="fa fa-plus"></span> Add Book</a></div>
            </h1>
		</div>
		<div class="row">
			<div id="reload"></div>
			<table class="table table-striped" id="table">
				<thead>
					<tr>
						<th>Title</th>
						<th>Author</th>
						<th>Published Date</th>
						<th>Total Page</th>
						<th>Category</th>
						<th style="text-align:right;">Action</th>
					</tr>
				</thead>
				<tbody></tbody>
				
			</table>
		</div>
	</div>

	<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
	<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
	<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
	<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>

	<script>
		let save_method
		let table

		// datatables
		$(document).ready(function(){
			table = $('#table').DataTable({
				// AJAX Source
				"ajax" : {
					"url" : "<?php echo site_url('books/bookList'); ?>",
					"type" : "POST"
				},
			})



			 //datepicker
		    $('.datepicker').datepicker({
		        autoclose: true,
		        format: "yyyy-mm-dd",
		        todayHighlight: true,
		        orientation: "top auto",
		        todayBtn: true,
		        todayHighlight: true,  
		    });
			table.fnSetColumnVis([0],false)
		})

		function edit_books(id) {
			save_method = 'update'
			$('#form')[0].reset()
			$('.form-group').removeClass('has-error')
			$('.help-block').empty()

			// Load Data
			$.ajax({
				url: "<?php echo base_url('books/editData/') ?>" +id,
				type: "GET",
				dataType: "json",
				success: function(data) {
					$('[name="id"]').val(data.id)
					$('[name="booktitle"]').val(data.title)
					$('[name="authorname"]').val(data.author)
					$('[name="pubdate"]').datepicker('update', data.date_published)
					$('[name="totalpages"]').val(data.total_pages)
					$('[name="booktype"]').val(data.title_category)
					$('#modal_form').modal('show')
				}
			})
		}

		function save() {
			$('#btnSave').text('saving...')
			$('#btnSave').attr('disabled',true)
			let url

			if(save_method == 'add') {
				url = "<?php echo base_url('books/submitAdd') ?>"
			}else {
				url = "<?php echo base_url('books/submitUpdate') ?>"
			}

			$.ajax({
				url : url,
				type:"POST",
				data:$('#form').serialize(),
				dataType:"JSON",
				success: function(data){
					if(data.status) {
						$('#modal_form').modal('hide');
                		reload_table();
					}
					$('#btnSave').text('save')
					$('#btnSave').attr('disabled',false)
				},
				error: function (jqXHR, textStatus, errorThrown)
		        {
		            alert('Error adding / update data');
		            $('#btnSave').text('save'); 
		            $('#btnSave').attr('disabled',false); 

		        }

			})
		}

		function delete_books(id) {
			if(confirm('Are you sure delete this data?')) {
				$.ajax({
					url : "<?php echo base_url('books/deleteData') ?>/"+id,
					type: "POST",
					dataType : "JSON",
					success : function(data) {
						$('#modal_form').modal('hide')
						reload_table()
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('Error Deleting data')
					}
				})
			}
		}

		function add_books() {
			save_method = 'add'
			$('#form')[0].reset()
			$('.form-group').removeClass('has-error')
			$('.help-block').empty()
			$('#modal_form').modal('show')
			$('.modal-title').text('Add Books')
		}

		function reload_table(){
		    table.ajax.reload()
		}

	</script>

	<!-- Bootstrap modal -->
	<div class="modal fade" id="modal_form" role="dialog">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h3 class="modal-title">Books Form</h3>
	            </div>
	            <div class="modal-body form">
	                <form action="#" id="form" class="form-horizontal">
	                    <input type="hidden" value="" name="id"/> 
	                    <div class="form-body">
	                        <div class="form-group">
	                            <label class="control-label col-md-3">Title</label>
	                            <div class="col-md-9">
	                                <input name="booktitle" placeholder="Book Title" class="form-control" type="text">
	                                <span class="help-block"></span>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="control-label col-md-3">Author</label>
	                            <div class="col-md-9">
	                                <input name="authorname" placeholder="Author Name" class="form-control" type="text">
	                                <span class="help-block"></span>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="control-label col-md-3">Published Date</label>
	                            <div class="col-md-9">
	                                <input name="pubdate" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
	                                <span class="help-block"></span>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="control-label col-md-3">Total Pages</label>
	                            <div class="col-md-9">
	                               <input name="totalpages" placeholder="Total Pages" class="form-control" type="text">
	                                <span class="help-block"></span>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="control-label col-md-3">Book Type</label>
	                            <div class="col-md-9">
	                                <select name="booktype" class="form-control">
	                                    <option value="">--Select Type--</option>
	                                    <option value="other">Other</option>
	                                    <option value="novel">Novel</option>
	                                    <option value="computer">Computer</option>
	                                </select>
	                                <span class="help-block"></span>
	                            </div>
	                        </div>
	                        
	                        
	                    </div>
	                </form>
	            </div>
	            <div class="modal-footer">
	                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
	                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
	            </div>
	        </div><!-- /.modal-content -->
	    </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- End Bootstrap modal -->
</body>
</html>