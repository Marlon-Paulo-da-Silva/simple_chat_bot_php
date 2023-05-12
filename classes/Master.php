<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}

	function save_response(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			// if(!in_array($k,['id', 'suggestion'])){
			if(!in_array($k,['id', 'keyword', 'suggestion'])){
				if(!empty($data)) $data .=",";
				$v = $this->conn->real_escape_string($v);
				if($k == 'response'){
					$data .= " `{$k}`='".mb_convert_encoding($v, 'ISO-8859-1', 'UTF-8')."' ";
				} else {
					$data .= " `{$k}`='{$v}' ";
				}
			}
		}
		// $kw_arr=[];
		// foreach($keyword as $k => $v){
		// 	$v  = trim($this->conn->real_escape_string($v));
		// 	$check = $this->conn->query("SELECT keyword FROM `chat_bot_keyword_list` where keyword = '{$v}'".(!empty($id) ? " and response_id != '{$id}' " : ""))->num_rows;
		// 	if($check > 0){
		// 		$resp['status'] = 'failed';
		// 		$resp['msg'] = 'Keyword already taken. This might complicate for fetching a response.';
		// 		$resp['kw_index'] = $k;
		// 		return json_encode($resp);
		// 	}
		// 	$kw_arr[]= $v;
		// }


		// $data = mb_convert_encoding($data, 'ISO-8859-1', 'UTF-8');

		if(empty($id)){
			$sql = "INSERT INTO `chat_bot_response_list` set {$data} ";
		}else{
			$sql = "UPDATE `chat_bot_response_list` set {$data} where id = '{$id}' ";
		}
			$save = $this->conn->query($sql);
		if($save){
			$rid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['rid'] = $rid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "Nova Resposta salva com successo.";
			else
				$resp['msg'] = " Resposta atualizada com successo.";
			$data2="";
			foreach($kw_arr as $kw){
				if(!empty($data2)) $data2 .= ", ";
				$data2 .= "('{$rid}', '{$kw}')";
			}


			
			// $sql2 = "INSERT INTO `chat_bot_keyword_list` (`response_id`, `keyword`) VALUES {$data2}";
			
			// $this->conn->query("DELETE FROM `chat_bot_keyword_list` where response_id = '{$rid}'");
			// $save2 = $this->conn->query($sql2);
			// if(!$save2){
			// 	if(empty($id))
			// 	$this->conn->query("DELETE FROM `chat_bot_keyword_list` where response_id = '{$rid}'");
			// 	$resp['status'] = 'failed';
			// 	$resp['msg'] = $this->conn->error;
			// 	$resp['sql'] = $sql2;
			// }
			$data3="";
			$this->conn->query("DELETE FROM `chat_bot_suggestion_list` where response_id = '{$rid}'");
			foreach($suggestion as $sg){
				if(empty($sg))
				continue;
				$sg = $this->conn->real_escape_string($sg);
				if(!empty($data3)) $data3 .= ", ";
				$data3 .= "('{$rid}', '{$sg}')";
			}


			// $data3 = mb_convert_encoding($data3, 'ISO-8859-1', 'UTF-8');
			
			if(!empty($data3)){
				$sql3 = "INSERT INTO `chat_bot_suggestion_list` (`response_id`, `suggestion`) VALUES {$data3}";
				$save3 = $this->conn->query($sql3);
				if(!$save3){
					if(empty($id))
					$this->conn->query("DELETE FROM `chat_bot_keyword_list` where response_id = '{$rid}'");
					$resp['status'] = 'failed';
					$resp['msg'] = $this->conn->error;
					$resp['sql'] = $sql3;
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] == 'success')
			$this->settings->set_flashdata('success',$resp['msg']);
			return json_encode($resp);
	}
	function delete_response(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `chat_bot_response_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Response successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function fetch_response(){
		extract($_POST);

		$token = 'RSFVNKM4SDC67NDVPTM7VOAEK576BOXQ';

		// /*call another API*/
		// $curl = curl_init();
		// curl_setopt_array($curl, array(
		// CURLOPT_URL => "https://api.wit.ai/message?v=20230505&q=$kw",
		// CURLOPT_RETURNTRANSFER => true,
		// CURLOPT_ENCODING => "",
		// CURLOPT_MAXREDIRS => 10,
		// CURLOPT_TIMEOUT => 30,
		// CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		// CURLOPT_CUSTOMREQUEST => "GET",
		// CURLOPT_HTTPHEADER => array(
		// 		"authorization: Bearer $token"
		// 	),
		// ));

		// 	$response = curl_exec($curl);
		// 	$err = curl_error($curl);


		$context = stream_context_create([
				'http' => [
						'method' => 'GET',
						'header' => [
								'Content-Type: application/json',
								"authorization: Bearer $token"
						]
				]
		]);

		$query = array(
			'q' => $kw
		);
		$url = 'https://api.wit.ai/message?v=20230505&' . http_build_query($query,'',null,PHP_QUERY_RFC3986);
	
		$resultado = file_get_contents($url, false, $context);

		// testar se está vindo o json
		// return $resultado;

		
		
		$resultado = json_decode($resultado, true);
		// testa o array que veio da api, precisa remover o alert do Home para não confundir
		// echo "<pre>";
		// print_r($resultado);
		// echo "</pre>";
		


		// echo "<pre>";
		// print_r($resultado['entities'][array_key_first($resultado['entities'])][0]['name']);
		// echo "</pre>";
		// exit();
			
		
		
		$sql = "SELECT * FROM `chat_bot_response_list` WHERE `entity` = '".$resultado['entities'][array_key_first($resultado['entities'])][0]['name']."'";
		// // $sql = "SELECT * FROM `chat_bot_response_list` where id in (SELECT response_id FROM `chat_bot_keyword_list` where `keyword` = '{$kw}')";

		

		$resp['sql'] = $sql;
		$qry = $this->conn->query($sql);
		if($qry){
			if($qry->num_rows > 0){
				$result = $qry->fetch_array();
				$resp['status'] = 'success';
				$resp['response'] = mb_convert_encoding($result['response'], 'UTF-8', 'ISO-8859-1');
				$resp['response'] = mb_convert_encoding($result['response'], 'UTF-8', 'ISO-8859-1');
				$sg_qry = $this->conn->query("SELECT suggestion FROM `chat_bot_suggestion_list` where response_id = '{$result['id']}'");
				if($sg_qry->num_rows > 0){
					$suggestions = array_column($sg_qry->fetch_all(MYSQLI_ASSOC), 'suggestion');
				}else{
					$suggestions = $this->settings->info('suggestion') != "" ? json_decode($this->settings->info('suggestion')) : "";
				}
				$resp['suggestions'] = mb_convert_encoding($suggestions, 'UTF-8', 'ISO-8859-1');
				if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
					$client = $_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					$client = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$client = $_SERVER['REMOTE_ADDR'];
				}
				$this->conn->query("INSERT INTO `chat_bot_keyword_fetched` set `response_id` = '{$result['id']}', `client` = '{$client}'");
			}else{
				$resp['status'] = 'success';
				$resp['response'] = $this->settings->info('no_answer');
			}
		}else{
			$resp['status'] = "failed";
			$resp['msg'] = $this->conn->error;
		}


		// teste resp
		// echo "<pre>";
		// print_r($resp);
		// echo "</pre>";


		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'save_response':
		echo $Master->save_response();
	break;
	case 'delete_response':
		echo $Master->delete_response();
	break;
	case 'fetch_response':
		echo $Master->fetch_response();
	break;
	default:
		// echo $sysset->index();
		break;
}