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
	<h4 class="my-3"><b>Detalhes da resposta</b></h4>
</div>
<div class="row mt-n5 justify-content-center">
	<div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-body">
				<div class="container-fluid">
                    <legend>Response</legend>
                    <div class="pl-3"><?= isset($response) ? $response : '' ?></div>
                    <!-- <fieldset>
                        <legend>Keywords</legend>
                        <ul class="list-group ml-3">
                            <?php if(isset($id)): ?>
                                <?php  
                                $kw_qry = $conn->query("SELECT * FROM `chat_bot_keyword_list` where response_id = '{$id}'");
                                while($row = $kw_qry->fetch_assoc()):	
                                ?>
                                <li class="list-group-item rounded-0"><?= $row['keyword'] ?></li>
                                <?php endwhile; ?>
							<?php endif; ?>
                        </ul>
                    </fieldset> -->
                    <fieldset>
                        <legend>Trait</legend>
                        <ul class="list-group ml-3">
                            <?php if(isset($id)): ?>
                           
                                <li class="list-group-item rounded-0"><?= isset($trait) ? $trait : '' ?></li>
                                
							<?php endif; ?>
                        </ul>
                    </fieldset>
                    <fieldset>
                        <legend>Intenty</legend>
                        <ul class="list-group ml-3">
                            <?php if(isset($id)): ?>
                               
                                <li class="list-group-item rounded-0"><?= $intent ?></li>

							<?php endif; ?>
                        </ul>
                    </fieldset>
                    <fieldset>
                        <legend>Entities</legend>
                        <ul class="list-group ml-3">
                            <?php if(isset($id)): ?>
                               
                                <li class="list-group-item rounded-0"><?= $entity1 ?></li>
                                <li class="list-group-item rounded-0"><?= $entity2 ?></li>
                                <li class="list-group-item rounded-0"><?= $entity3 ?></li>
                                <li class="list-group-item rounded-0"><?= $entity4 ?></li>
                                <li class="list-group-item rounded-0"><?= $entity5 ?></li>
                                <li class="list-group-item rounded-0"><?= $entity6 ?></li>
							<?php endif; ?>
                        </ul>
                    </fieldset>
                    <fieldset>
                        <legend>Suggestions</legend>
                        <ul class="list-group ml-3">
                            <?php if(isset($id)): ?>
                                <?php  
                                $sg_qry = $conn->query("SELECT * FROM `chat_bot_suggestion_list` where response_id = '{$id}'");
                                while($row = $sg_qry->fetch_assoc()):	
                                ?>
                                <li class="list-group-item rounded-0"><?= $row['suggestion'] ?></li>
                                <?php endwhile; ?>
							<?php endif; ?>
                        </ul>
                    </fieldset>
				</div>
			</div>
			<div class="card-footer py-1 text-center">
				<a class="btn btn-sm btn-primary bg-gradient-primary rounded-0" href="./?page=responses/manage_response&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Editar</a>
				<a class="btn btn-sm btn-light bg-gradient-light border rounded-0" href="./?page=responses"><i class="fa fa-angle-left"></i> Voltar para a lista</a>
			</div>
		</div>
	</div>
</div>