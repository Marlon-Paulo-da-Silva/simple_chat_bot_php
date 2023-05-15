<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
	.prod-img{
		width: 5em;
    	max-height: 8em;
		object-fit:scale-down;
		object-position:center center;
	}
</style>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">Lista de respostas</h3>
		<div class="card-tools">
			<a href="./?page=responses/manage_response" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Criar nova</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="25%">
					<col width="25%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Data de criação</th>
						<th>Resposta</th>
						<th>Trait</th>
						<th>Intent</th>
						<th>Entity1</th>
						<th>Entity2</th>
						<th>Entity3</th>
						<th>Entity4</th>
						<th>Entity5</th>
						<th>Entity6</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT * from `chat_bot_response_list`  order by unix_timestamp(`date_created`) desc ");
						while($row = $qry->fetch_assoc()):
							$kw_qry = $conn->query("SELECT * FROM chat_bot_keyword_list where response_id = '{$row['id']}'");
							// $kws = array_column($kw_qry->fetch_all(MYSQLI_ASSOC), 'keyword');
							// if(count($kws)){
							// 	$kws = implode(", ",$kws);
							// }else{
							// 	$kws = "N/A";
							// }
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("d-m-Y H:i",strtotime($row['date_created'])) ?></td>
							<td><p class="truncate-1 m-0"><?php echo strip_tags($row['response']) ?></p></td>
							<td><?= $row['trait'] ?></td>
							<td><?= $row['intent'] ?></td>
							<td><?= $row['entity1'] ?></td>
							<td><?= $row['entity2'] ?></td>
							<td><?= $row['entity3'] ?></td>
							<td><?= $row['entity4'] ?></td>
							<td><?= $row['entity5'] ?></td>
							<td><?= $row['entity6'] ?></td>
							<td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-success px-3 rounded-pill">Ativo</span>
                                <?php else: ?>
                                    <span class="badge badge-danger px-3 rounded-pill">Inativo</span>
                                <?php endif; ?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Ação
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <!-- <a class="dropdown-item view_data" href="./?page=responses/view_response&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a>
				                    <div class="dropdown-divider"></div> -->
				                    <a class="dropdown-item edit_data" href="./?page=responses/manage_response&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Apagar</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this response permanently?","delete_response",[$(this).attr('data-id')])
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [6] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_response($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_response",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>


