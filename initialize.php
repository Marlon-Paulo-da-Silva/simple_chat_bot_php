<?php
// $dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'admin','password'=>'0192023a7bbd73250516f069df18b500','last_login'=>'','date_updated'=>'','date_added'=>'');
// if(!defined('base_url')) define('base_url','http://localhost/simple_chat_bot/');
// if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
// // if(!defined('dev_data')) define('dev_data',$dev_data);
// if(!defined('DB_SERVER')) define('DB_SERVER',"localhost");
// if(!defined('DB_USERNAME')) define('DB_USERNAME',"root");
// if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"");
// if(!defined('DB_NAME')) define('DB_NAME',"chat_bot_db");
?>

<?php

include_once __DIR__ . "/../../../../sissistema/funcoes_conexao.php";

$dataConexao = databaseConexao_retorno('local');

$dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'admin','password'=>'0192023a7bbd73250516f069df18b500','last_login'=>'','date_updated'=>'','date_added'=>'');
if(!defined('base_url')) define('base_url', $_SERVER['REDIRECT_SCRIPT_URI']);
if(!defined('base_app')) define('base_app', str_replace('\\','/',$_SERVER['REDIRECT_SCRIPT_URI']).'/' );
// if(!defined('dev_data')) define('dev_data',$dev_data);
if(!defined('DB_SERVER')) define('DB_SERVER',$dataConexao['url']);
if(!defined('DB_USERNAME')) define('DB_USERNAME',$dataConexao['usuario']);
if(!defined('DB_PASSWORD')) define('DB_PASSWORD',$dataConexao['senha']);
if(!defined('DB_NAME')) define('DB_NAME',$dataConexao['banco']);
?>