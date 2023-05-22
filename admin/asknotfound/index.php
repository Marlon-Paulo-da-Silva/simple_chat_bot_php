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
		<h3 class="card-title">Perguntas não encontradas</h3>
		<div class="card-tools">
			<a href="./?page=responses/manage_response" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Criar nova</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="3%">
					<col width="25%">
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="7%">
					<col width="5%">
				</colgroup>
				<thead>
					<tr>
						<th style="text-align: center;">#</th>
						<th>Pergunta</th>
						<th>Cad</th>
						<th>Site</th>
						<th>Nome</th>
						<th>Data</th>
						<th>Ação</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT *, ( SELECT cadastro.website_cad FROM cadastro WHERE cadastro.codigo_cad = chat_bot_question_not_found_list.cod_cad LIMIT 1 ) AS site,( SELECT cadastro_usuarios.nome_cus FROM cadastro_usuarios WHERE ( chat_bot_question_not_found_list.cod_cad = cadastro_usuarios.codigo_cad AND chat_bot_question_not_found_list.cod_usu = cadastro_usuarios.codigo_cus ) LIMIT 1 ) AS nome_usu,( SELECT cadastro.nomefantasia_cad FROM cadastro WHERE ( chat_bot_question_not_found_list.cod_cad = cadastro.codigo_cad ) LIMIT 1 ) AS nome_adm FROM `chat_bot_question_not_found_list` ORDER BY UNIX_TIMESTAMP(`date_created`) DESC");
						while($row = $qry->fetch_assoc()):
							// $kws = array_column($kw_qry->fetch_all(MYSQLI_ASSOC), 'keyword');
							// if(count($kws)){
							// 	$kws = implode(", ",$kws);
							// }else{
							// 	$kws = "N/A";
							// }
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?= $row['question'] ?></td>
							<td><?= $row['cod_cad'] ?></td>
							<td><?= $row['site'] ?></td>
							<td><?= ($row['nome_usu'] == NULL ? $row['nome_adm'] : $row['nome_usu']) ?></td>
							<td><?php echo date("d/m/Y H:i",strtotime($row['date_created'])) ?></td>
					    <td align="center">
								 <button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Ação
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <!-- <a class="dropdown-item view_data" href="./?page=responses/view_response&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a>
				                    <div class="dropdown-divider"></div> -->
				                    <!-- <a class="dropdown-item edit_data" href="./?page=responses/manage_response&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
				                    <div class="dropdown-divider"></div> -->
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
			_conf("Tem a certeza de que pretende apagar esta resposta permanentemente?","delete_response",[$(this).attr('data-id')])
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [11] }
			],
			language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
    	},
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_response($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_asknotfound",
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


