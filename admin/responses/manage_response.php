<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `chat_bot_response_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="content px-2 py-5 bg-gradient-primary">
	<h4 class="my-3"><b><?= !isset($id) ? "Criar nova resposta" : "Atualizar resposta" ?></b></h4>
</div>
<div class="row mt-n5 justify-content-center">
	<div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-body">
				<div class="container-fluid">
					<form action="" id="response-form">
						<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
						<div class="form-group">
							<label for="response_ckeditor" class="control-label">Descrição</label>
							<textarea type="text" name="response" id="response_ckeditor" class="form-control form-control-sm rounded-0" required><?php echo isset($response) ? $response : ''; ?></textarea>
						</div>
						<div class="form-group">
							<label for="status" class="control-label">Status</label>
							<select name="status" id="status" class="form-control form-control-sm rounded-0" required>
								<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
								<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
							</select>
						</div>
						<div class="form-group">
							<label for="category" class="control-label">Categoria</label>
							<select name="category" id="category" class="form-control form-control-sm rounded-0" required>

								<?php  
									$cat_qry = $conn->query("SELECT * FROM `chat_bot_category_list`");
									while($row = $cat_qry->fetch_assoc()):	
								?>
									<option value="<?= $row['id']; ?>" <?php echo isset($category) && $category == $row['id'] ? 'selected' : '' ?>><?= $row['name']; ?></option>

								<?php endwhile; ?>
							
							</select>
						</div>

						<div class="clear-fix mt-3"></div>
						<div class="row bg-gradient-primary">
							<div class="col-12 border m-0 px-2 py-1">Trait</div>
						</div>
						<div id="entity-list" class="mb-3">
							<div class="row bg-gradient-light align-items-center kw-item" style="height:4.5em">
								<div class="col-12 border m-0 px-2 py-1 h-100">
									<textarea name="trait" cols="30" rows="2" class="form-control form-control-sm rounded-0" required="required" style="resize:none"><?php echo isset($trait) ? $trait: '' ?></textarea>
								</div>								
							</div>
							
						</div>

						<div class="clear-fix mt-3"></div>
						<div class="row bg-gradient-primary">
							<div class="col-12 border m-0 px-2 py-1">Intent</div>
						</div>
						<div id="entity-list" class="mb-3">
							<div class="row bg-gradient-light align-items-center kw-item" style="height:4.5em">
								<div class="col-12 border m-0 px-2 py-1 h-100">
									<textarea name="intent" cols="30" rows="2" class="form-control form-control-sm rounded-0" required="required" style="resize:none"><?php echo isset($intent) ? $intent: '' ?></textarea>
								</div>								
							</div>
							
						</div>

						<!-- <div class="clear-fix mt-3"></div>
						<div class="row bg-gradient-primary">
							<div class="col-11 border m-0 px-2 py-1">Keyword</div>
							<div class="col-1 border m-0 px-2 py-1">Action</div>
						</div>
						<div id="keyword-list" class="mb-3">
							<?php if(isset($id)): ?>
							<?php  
							$kw_qry = $conn->query("SELECT * FROM `chat_bot_keyword_list` where response_id = '{$id}'");
							while($row = $kw_qry->fetch_assoc()):	
							?>
							<div class="row bg-gradient-light align-items-center kw-item" style="height:4.5em">
								<div class="col-11 border m-0 px-2 py-1 h-100">
									<textarea name="keyword[]" cols="30" rows="2" class="form-control form-control-sm rounded-0" required="required" style="resize:none"><?= $row['keyword'] ?></textarea>
								</div>
								<div class="col-1 border m-0 px-2 py-1 text-center align-items-center h-100 justify-content-center d-flex">
									<div class="col-auto">
										<button class="btn-outline-danger btn btn-sm rounded-0 rem-item" type="button"><i class="fa fa-times"></i></button>
									</div>
								</div>
							</div>
							<?php endwhile; ?>
							<?php else: ?>
							<div class="row bg-gradient-light align-items-center kw-item" style="height:4.5em">
								<div class="col-11 border m-0 px-2 py-1 h-100">
									<textarea name="keyword[]" cols="30" rows="2" class="form-control form-control-sm rounded-0" required="required" style="resize:none"></textarea>
								</div>
								<div class="col-1 border m-0 px-2 py-1 text-center align-items-center h-100 justify-content-center d-flex">
									<div class="col-auto">
										<button class="btn-outline-danger btn btn-sm rounded-0 rem-item" type="button"><i class="fa fa-times"></i></button>
									</div>
								</div>
							</div>
							<?php endif; ?>
						</div>
						<div class="text-right">
							<button class="btn btn-primary btn-sm rounded-0" type="button" id="add_kw_item"><i class="far fa-plus-square mb-n2 mr-1"></i>Add Keyword Item</button>
						</div> -->

						<div class="clear-fix mt-3"></div>
						<div class="row bg-gradient-primary">
							<div class="col-12 border m-0 px-2 py-1">Entities</div>
						</div>
						<div id="entity-list" class="mb-3">
							<div class="row bg-gradient-light align-items-center kw-item" style="height:4.5em">
								<div class="col-12 border m-0 px-2 py-1 h-100">
									<textarea name="entity1" cols="30" rows="2" class="form-control form-control-sm rounded-0" style="resize:none"><?php echo isset($entity1) ? $entity1: '' ?></textarea>
								</div>
							</div>
							<div class="row bg-gradient-light align-items-center kw-item" style="height:4.5em">
							<div class="col-12 border m-0 px-2 py-1 h-100">
									<textarea name="entity2" cols="30" rows="2" class="form-control form-control-sm rounded-0" style="resize:none"><?php echo isset($entity2) ? $entity2: '' ?></textarea>
								</div>
							</div>
							<div class="row bg-gradient-light align-items-center kw-item" style="height:4.5em">
								<div class="col-12 border m-0 px-2 py-1 h-100">
									<textarea name="entity3" cols="30" rows="2" class="form-control form-control-sm rounded-0" style="resize:none"><?php echo isset($entity3) ? $entity3: '' ?></textarea>
								</div>
							</div>
							<div class="row bg-gradient-light align-items-center kw-item" style="height:4.5em">
								<div class="col-12 border m-0 px-2 py-1 h-100">
									<textarea name="entity4" cols="30" rows="2" class="form-control form-control-sm rounded-0" style="resize:none"><?php echo isset($entity4) ? $entity4: '' ?></textarea>
								</div>
							</div>
							<div class="row bg-gradient-light align-items-center kw-item" style="height:4.5em">
								<div class="col-12 border m-0 px-2 py-1 h-100">
									<textarea name="entity5" cols="30" rows="2" class="form-control form-control-sm rounded-0" style="resize:none"><?php echo isset($entity5) ? $entity5: '' ?></textarea>
								</div>
							</div>
							<div class="row bg-gradient-light align-items-center kw-item" style="height:4.5em">
								<div class="col-12 border m-0 px-2 py-1 h-100">
									<textarea name="entity6" cols="30" rows="2" class="form-control form-control-sm rounded-0" style="resize:none"><?php echo isset($entity6) ? $entity6: '' ?></textarea>
								</div>
							</div>
						</div>
				
						
						<div class="clear-fix mt-3"></div>
						<div class="row bg-gradient-primary">
							<div class="col-11 border m-0 px-2 py-1">Sugestão</div>
							<div class="col-1 border m-0 px-2 py-1">Ação</div>
						</div>
						<div id="suggestion-list" class="mb-3">
							<?php if(isset($id)): ?>
							<?php  
							$sg_qry = $conn->query("SELECT * FROM `chat_bot_suggestion_list` where response_id = '{$id}'");
							while($row = $sg_qry->fetch_assoc()):	
							?>
							<div class="row bg-gradient-light align-items-center sg-item" style="height:4.5em">
								<div class="col-11 border m-0 px-2 py-1 h-100">
									<textarea name="suggestion[]" cols="30" rows="2" class="form-control form-control-sm rounded-0" style="resize:none"><?= $row['suggestion'] ?></textarea>
								</div>
								<div class="col-1 border m-0 px-2 py-1 text-center align-items-center h-100 justify-content-center d-flex">
									<div class="col-auto">
										<button class="btn-outline-danger btn btn-sm rounded-0 rem-item" type="button"><i class="fa fa-times"></i></button>
									</div>
								</div>
							</div>
							<?php endwhile; ?>
							<?php endif; ?>
							<div class="row bg-gradient-light align-items-center sg-item" style="height:4.5em">
								<div class="col-11 border m-0 px-2 py-1 h-100">
									<textarea name="suggestion[]" cols="30" rows="2" class="form-control form-control-sm rounded-0" style="resize:none"></textarea>
								</div>
								<div class="col-1 border m-0 px-2 py-1 text-center align-items-center h-100 justify-content-center d-flex">
									<div class="col-auto">
										<button class="btn-outline-danger btn btn-sm rounded-0 rem-item" type="button"><i class="fa fa-times"></i></button>
									</div>
								</div>
							</div>
						</div>
						<div class="text-right">
							<button class="btn btn-primary btn-sm rounded-0" type="button" id="add_suggestion_item"><i class="far fa-plus-square mb-n2 mr-1"></i>Adicionar + Item Sugestão</button>
						</div>
					</form>
				</div>
			</div>
			<div class="card-footer py-1 text-center">
				<button class="btn btn-sm btn-primary bg-gradient-primary rounded-0" form="response-form"><i class="fa fa-save"></i> Salvar</button>
				<a class="btn btn-sm btn-light bg-gradient-light border rounded-0" href="./?page=responses"><i class="fa fa-angle-left"></i> Cancelar</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="../../../../../../Scripts/ckeditor4.10/ckeditor.js"></script>

<script>
	$(document).ready(function(){
		$('#keyword-list .kw-item').find('.rem-item').click(function(){
			if($('#keyword-list .kw-item').length > 1){
				$(this).closest('.kw-item').remove()
			}else{
				$(this).closest('.kw-item').find('[name="keyword[]"]').val('').focus()
			}
		})
		$('#suggestion-list .sg-item').find('.rem-item').click(function(){
			if($('#suggestion-list .sg-item').length > 1){
				$(this).closest('.sg-item').remove()
			}else{
				$(this).closest('.sg-item').find('[name="suggestion[]"]').val('').focus()
			}
		})
		$('#add_kw_item').click(function(){
			var item = $('#keyword-list .kw-item:nth-child(1)').clone()
			item.find('[name="keyword[]"]').val('').removeClass('border-danger')
			$('#keyword-list').append(item)
			item.find('[name="keyword[]"]').focus()
			item.find('.rem-item').click(function(){
				if($('#keyword-list .kw-item').length > 1){
					item.remove()
				}else{
					item.find('[name="keyword[]"]').val('').focus()
				}
			})
		})
		$('#add_suggestion_item').click(function(){
			var item = $('#suggestion-list .sg-item:nth-child(1)').clone()
			item.find('[name="suggestion[]"]').val('').removeClass('border-danger')
			$('#suggestion-list').append(item)
			item.find('[name="suggestion[]"]').focus()
			item.find('.rem-item').click(function(){
				if($('#suggestion-list .sg-item').length > 1){
					item.remove()
				}else{
					item.find('[name="suggestion[]"]').val('').focus()
				}
			})
		})
		// $('#response').summernote({
		// 	height: "15em",
		// 	toolbar: [
		// 		[ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		// 		[ 'fontname', [ 'fontname' ] ],
		// 		[ 'fontsize', [ 'fontsize' ] ],
		// 		[ 'color', [ 'color' ] ],
		// 		[ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		// 		['insert', ['link', 'picture', 'video']],
		// 		[ 'view', [ 'undo', 'redo' ] ]
		// 	]
		// })
		$('#response-form').submit(function(e){
			e.preventDefault();
			var _this = $(this)
			$('.err-msg').remove();
			$('.border-danger').removeClass('border-danger')
			var el = $('<div>')
			el.addClass("alert alert-danger err-msg")
			el.hide()
			var content = '';

			for(instance in CKEDITOR.instances){
				content = CKEDITOR.instances[instance].getData();
			}
		
			document.getElementById('response_ckeditor').value = content;

			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_response",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					// end_loader();
				},
				success:function(resp){
					console.log(resp)
					if(typeof resp =='object' && resp.status == 'success'){
						location.href = './?page=responses/view_response&id='+resp.rid
					}else if(resp.status == 'failed' && !!resp.msg){
						// if('kw_index' in resp){
						// 	$('[name="keyword[]"]').eq(resp.kw_index).addClass('border-danger').focus()
						// 	$('[name="keyword[]"]').eq(resp.kw_index)[0].setCustomValidity(resp.msg)
						// 	$('[name="keyword[]"]').eq(resp.kw_index)[0].reportValidity()
						// 	$('[name="keyword[]"]').eq(resp.kw_index).on('input', function(){
						// 		$(this).removeClass('border-danger')
						// 		$(this)[0].setCustomValidity("")
						// 	})
						// }else{
            //                 el.text(resp.msg)
            //                 _this.prepend(el)
            //                 el.show('slow')
            //                 $("html, body,.modal").scrollTop(0);
						// }
          }else{
						alert_toast("An error occured",'error');
					}
					end_loader()
				}
			})
		})

	})
</script>



<?

function CKeditor($caixa,$q)
{
	if ($q==1)
	{
		echo "<script type='text/javascript'>CKEDITOR.replace( '".$caixa."' );</script>";
	}
	elseif ($q==2)
	{
		echo "
		<script type='text/javascript'>
			CKEDITOR.replace( '".$caixa."', {
				height: '300px',
				toolbar: [
					{ name: 'basicstyles', items: [ 'Source','-', 'TextColor', 'BGColor' ,'-', 'Bold', 'Italic', 'Underline', 'Strike', '-',
								'TransformTextToUppercase', 'TransformTextToLowercase', 'TransformTextCapitalize' ] }, 
					{ name: 'links', items: [ 'Link', 'Image', 'HorizontalRule', 'Table', ] },
					{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 
								'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
					{ name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] }
				]
			});	
		</script>	
		";	
	}
}

CKeditor('response_ckeditor',2);

?>

