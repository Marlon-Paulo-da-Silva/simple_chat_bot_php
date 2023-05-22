<?php

if (isset($_GET['id']) && $_GET['id'] > 0) {
  $qry = $conn->query("SELECT *, ( SELECT cadastro.website_cad FROM cadastro WHERE cadastro.codigo_cad = chat_bot_historico_conversa.cod_cad LIMIT 1 ) AS site FROM `chat_bot_historico_conversa` where id = '{$_GET['id']}' ");
  if ($qry->num_rows > 0) {
    foreach ($qry->fetch_assoc() as $k => $v) {
      $$k = $v;
    }
  }
}
?>
<div class="content px-2 py-5 bg-gradient-primary">
  <h4 class="my-3"><b>Detalhes do histórico</b></h4>
</div>
<div class="row mt-n5 justify-content-center">
  <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
    <div class="card rounded-0 shadow">
      <div class="card-body">
        <div class="container-fluid">
          <legend>Nome</legend>
          <div class="pl-3"><?= isset($nome_usu) ? $nome_usu : '' ?></div>
          <!-- <fieldset>
                        <legend>Keywords</legend>
                        <ul class="list-group ml-3">
                    <?php if (isset($id)) : ?>
                              
							      <?php endif; ?>
                        </ul>
                    </fieldset> -->
          
          <fieldset>
            <legend>Site</legend>
            <ul class="list-group ml-3">
              <?php if (isset($id)) : ?>

                <li class="list-group-item rounded-0"><?= $site ?></li>

              <?php endif; ?>
            </ul>
          </fieldset>
          <fieldset>
            <legend>Data de atualização</legend>
            <ul class="list-group ml-3">
              <?php if (isset($id)) : ?>

                <li class="list-group-item rounded-0"><?= $data_atualiz ?></li>
               
              <?php endif; ?>
            </ul>
          </fieldset>
          <fieldset>
            <legend>Data da criação</legend>
            <ul class="list-group ml-3">
              <?php if (isset($id)) : ?>

                <li class="list-group-item rounded-0"><?= $data_conv ?></li>
               
              <?php endif; ?>
            </ul>
          </fieldset>
          <fieldset>
            <legend>Conversa</legend>
            <ul class="list-group ml-3">
              <?php if (isset($id)) : ?>

                <li class="list-group-item rounded-0"><?= isset($conversa) ? $conversa : '' ?></li>

              <?php endif; ?>
            </ul>
          </fieldset>
        </div>
      </div>
      <div class="card-footer py-1 text-center">
        <a class="btn btn-sm btn-light bg-gradient-light border rounded-0" href="./?page=history"><i class="fa fa-angle-left"></i> Voltar para a lista</a>
      </div>
    </div>
  </div>
</div>