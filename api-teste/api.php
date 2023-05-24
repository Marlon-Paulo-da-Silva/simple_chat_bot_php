<?php 

	require_once('SystemSettings.php');


	class Api extends Rest {

		private $settings;
		
		public function __construct() {
			global $_settings;
			$this->settings = new SystemSettings;
			parent::__construct();
		}

		public function generateToken() {
			$email = $this->validateParameter('email', $this->param['email'], STRING);
			$pass = $this->validateParameter('pass', $this->param['pass'], STRING);
			try {
				$stmt = $this->dbConn->prepare("SELECT * FROM chat_bot_users WHERE email = :email AND password = :pass");
				// $stmt->bindParam(":email", $email);
				// $stmt->bindParam(":pass", $pass);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				if(!is_array($user)) {
					$this->returnResponse(INVALID_USER_PASS, "Email or Password is incorrect.");
				}

				// if( $user['active'] == 0 ) {
				// 	$this->returnResponse(USER_NOT_ACTIVE, "User is not activated. Please contact to admin.");
				// }

				$paylod = [
					'iat' => time(),
					'iss' => 'localhost',
					'exp' => time() + (15*60),
					'userId' => $user['id']
				];

				$token = JWT::encode($paylod, SECRETE_KEY);
				
				$data = ['token' => $token];
				$this->returnResponse(SUCCESS_RESPONSE, $data);
			} catch (Exception $e) {
				$this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
			}
		}

		public function addCustomer() {
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$email = $this->validateParameter('email', $this->param['email'], STRING, false);
			$addr = $this->validateParameter('addr', $this->param['addr'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);

			$cust = new Customer;
			$cust->setName($name);
			$cust->setEmail($email);
			$cust->setAddress($addr);
			$cust->setMobile($mobile);
			$cust->setCreatedBy($this->userId);
			$cust->setCreatedOn(date('Y-m-d'));

			if(!$cust->insert()) {
				$message = 'Failed to insert.';
			} else {
				$message = "Inserted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function getResponses() {

			$resp = new Response;
			// // $cust->setId($customerId);
			$response = $resp->getAllResponses();
			if(!is_array($response)) {
				$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'response details not found.']);
			}

			$response = mb_convert_encoding($response, 'UTF-8', 'ISO-8859-1');
			
			

			$this->returnResponse(SUCCESS_RESPONSE, $response);
		}

		public function getResponse() {

			// $resp = new Response;
			$kw = $this->validateParameter('kw', $this->param['kw'], STRING, true);



			// $response = $resp->getResponse();
			
			// if(!is_array($response)) {
				// 	$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'response details not found.']);
				// }


			$response = $this->fetch_response($kw, $this->param['cod_cad'], $this->param['cod_usu']);

			function utf8ize($d) {
					if (is_array($d)) {
							foreach ($d as $k => $v) {
									$d[$k] = mb_convert_encoding($v, 'UTF-8', 'ISO-8859-1');
							}
					} else if (is_string ($d)) {
							return mb_convert_encoding($d, 'UTF-8', 'ISO-8859-1');
					}
					return $d;
			}

			// echo $response; exit;
				
			$this->returnResponse(SUCCESS_RESPONSE, utf8ize($response));
			// $this->returnResponse(SUCCESS_RESPONSE, 'teste response');
		}
		
		public function getCustomerDetails() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

			$cust = new Customer;
			$cust->setId($customerId);
			$customer = $cust->getCustomerDetailsById();
			if(!is_array($customer)) {
				$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Customer details not found.']);
			}

			$response['customerId'] 	= $customer['id'];
			$response['cutomerName'] 	= $customer['name'];
			$response['email'] 			= $customer['email'];
			$response['mobile'] 		= $customer['mobile'];
			$response['address'] 		= $customer['address'];
			$response['createdBy'] 		= $customer['created_user'];
			$response['lastUpdatedBy'] 	= $customer['updated_user'];
			$this->returnResponse(SUCCESS_RESPONSE, $response);
		}

		public function updateCustomer() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$addr = $this->validateParameter('addr', $this->param['addr'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);

			$cust = new Customer;
			$cust->setId($customerId);
			$cust->setName($name);
			$cust->setAddress($addr);
			$cust->setMobile($mobile);
			$cust->setUpdatedBy($this->userId);
			$cust->setUpdatedOn(date('Y-m-d'));

			if(!$cust->update()) {
				$message = 'Failed to update.';
			} else {
				$message = "Updated successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function deleteCustomer() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

			$cust = new Customer;
			$cust->setId($customerId);

			if(!$cust->delete()) {
				$message = 'Failed to delete.';
			} else {
				$message = "deleted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function fetch_response($kw, $cad, $cod_usu = NULL){
			// extract($_POST);
	
			$token = 'EOKMGFERGAHRMJ6R6CPA6OT6JGTM45AS';
	
	
			$context = stream_context_create([
					'http' => [
							'method' => 'GET',
							'header' => [
									'Content-Type: application/json',
									"authorization: Bearer $token"
							]
					]
			]);

			$db = new DbConnect();

			$kw = mysqli_real_escape_string($db->conn, $kw);
	
			$query = array(
				'q' => $kw
			);
			$url = 'https://api.wit.ai/message?v=20230517&' . http_build_query($query,'',null,PHP_QUERY_RFC3986);
		
			$resultado = file_get_contents($url, false, $context);
	
			// testar se está vindo o json
			// return $resultado;

			
			
	
			
			
			$resultado = json_decode($resultado, true);
			// testa o array que veio da api, precisa remover o alert do Home para não confundir
			// echo "<pre>";
			// print_r($resultado);
			// echo "</pre>";

			// testar se está vindo o json (insomnia)
			// return $resultado;

			
			
				
			

			$resp = [];


			$countTraits = 0;
			$countEntities = 0;

			$resultado['text'] = mb_convert_encoding($resultado['text'], 'ISO-8859-1', 'UTF-8');

			$sql = "SELECT * FROM `chat_bot_response_list` WHERE `question` = '". trim($resultado['text']) ."'";
			// $sql = "SELECT * FROM `chat_bot_response_list` WHERE `question` LIKE '%". $resultado['text'] ."%'";

			return $sql;

			$qry = $db->conn->query($sql);

			if($qry){

				if($qry->num_rows > 0){

					$result = $qry->fetch_array();
					$resp['response'] = $result['response'];

				}else{

					
					if(is_countable($resultado['traits'])) {
						$countTraits = count($resultado['traits']);
						$countEntities = count($resultado['entities']);
					
		
						if(count($resultado['traits']) > 0){
		
							$traitMaior = $resultado['traits'][array_key_first($resultado['traits'])][0]['value'];
							$confidenceComparacao = $resultado['traits'][array_key_first($resultado['traits'])][0]['confidence'];
		
							// $resultado['traits'][array_key_first($resultado['traits'])][$i]['confidence'];
							
		
							
								if(count($resultado['traits']) > 1){
		
									foreach($resultado['traits'] as $key => $value){
										
		
										if($resultado['traits'][$key][0]['confidence'] > $confidenceComparacao){
											$traitMaior = $resultado['traits'][$key][0]['value'];
											$confidenceComparacao = $resultado['traits'][$key][0]['confidence'];
										}
									}
							
								}
		
								// $arrayTeste['traitMaior'] = $traitMaior;
								// $arrayTeste['confidenceComparacao'] = $confidenceComparacao;
		
								// return $arrayTeste;
		
		
		
		
								if($confidenceComparacao < 0.9){
		
									if(count($resultado['traits']) == 1){
										$sql = "SELECT * FROM `chat_bot_response_list` WHERE `trait` = '". $traitMaior ."'";
		
										$qry = $db->conn->query($sql);
		
										if($qry){
		
											if($qry->num_rows > 0){
		
												$result = $qry->fetch_array();
												$resp['response'] = $result['response'];
												// $resp['response'] = mb_convert_encoding($result['response'], 'UTF-8', 'ISO-8859-1');
		
												// $sg_qry = $db->conn->query("SELECT suggestion FROM `chat_bot_suggestion_list` where response_id = '{$result['id']}'");
												// if($sg_qry->num_rows > 0){
												// 	$suggestions = array_column($sg_qry->fetch_all(MYSQLI_ASSOC), 'suggestion');
												// }else{
												// 	$suggestions = $this->settings->info('suggestion') != "" ? json_decode($this->settings->info('suggestion')) : "";
												// }
												// $resp['suggestions'] = $suggestions;
		
		
												if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
													$client = $_SERVER['HTTP_CLIENT_IP'];
												} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
													$client = $_SERVER['HTTP_X_FORWARDED_FOR'];
												} else {
													$client = $_SERVER['REMOTE_ADDR'];
												}
												$db->conn->query("INSERT INTO `chat_bot_keyword_fetched` set `response_id` = '{$result['id']}', `client` = '{$client}'");
											}else{
		
												if(count($resultado['entities']) > 0 && count($resultado['intents']) > 0){
										
													$sqlWhereIn = " WHERE ";
						
													foreach($resultado['entities'] as $key => $value){
						
													$sqlWhereIn .= " ( ";	
							
													for ($i=1; $i <= 6; $i++) { 
							
														$sqlWhereIn .= " entity".$i." IN(";
							
														
															// // $key = explode(':', $key);
															// // echo "<pre>";
															// // print_r($key[0]);
															// // echo "</pre>";
															// echo "<pre>";
															// print_r($value[0]['name']);
															// echo "</pre>";
									
															$sqlWhereIn .= "'" . $value[0]['name'] . "',";
									
															
															$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-1);
															$sqlWhereIn .= ") OR";
														}
						
														$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
														$sqlWhereIn .= ") AND ";
														
													}
									
													
							
													// $sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
							
													$sqlWhereIn .= " intent IN('". $resultado['intents'][array_key_first($resultado['intents'])]['name'] ."') ";
													$sqlWhereIn .= "LIMIT 1";
												
													// echo "<pre>";
													// print_r($sqlWhereIn);
													// echo "</pre>";
													// return;
							
							
													$sql = "SELECT * FROM `chat_bot_response_list`" . $sqlWhereIn;
							
													// $sql = "SELECT response FROM `chat_bot_response_list` WHERE entity1 IN('email','criar') OR entity2 IN('email','criar') OR entity3 IN('email','criar') OR entity4 IN('email','criar') OR entity5 IN('email','criar') OR entity6 IN('email','criar') LIMIT 1";
							
													// return $sql;
										
													$qry = $db->conn->query($sql);
							
													if($qry){
							
														if($qry->num_rows > 0){
							
															$result = $qry->fetch_array();
															$resp['response'] = mb_convert_encoding($result['response'], 'UTF-8', 'ISO-8859-1');
															// $sg_qry = $db->conn->query("SELECT suggestion FROM `chat_bot_suggestion_list` where response_id = '{$result['id']}'");
															// if($sg_qry->num_rows > 0){
															// 	$suggestions = array_column($sg_qry->fetch_all(MYSQLI_ASSOC), 'suggestion');
															// }else{
															// 	$suggestions = $this->settings->info('suggestion') != "" ? json_decode($this->settings->info('suggestion')) : "";
															// }
															// $resp['suggestions'] = mb_convert_encoding($suggestions, 'UTF-8', 'ISO-8859-1');
															if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
																$client = $_SERVER['HTTP_CLIENT_IP'];
															} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
																$client = $_SERVER['HTTP_X_FORWARDED_FOR'];
															} else {
																$client = $_SERVER['REMOTE_ADDR'];
															}
															$db->conn->query("INSERT INTO `chat_bot_keyword_fetched` set `response_id` = '{$result['id']}', `client` = '{$client}'");
														}else{
							
															if(count($resultado['intents']) > 0){
																$resp['status'] = 'success';
																// $resp['response'] = $this->settings->info('no_answer');
																$resp['response'] = 'Poderia ser mais específico em relação ao que você quer ' . $resultado['intents'][array_key_first($resultado['intents'])]['name'] . '?';
																$this->saveQuestionNotFound($kw, $cad, $cod_usu);
															}
							
															if(count($resultado['intents']) < 0){
																$resp['status'] = 'success';
																// $resp['response'] = $this->settings->info('no_answer');
																$resp['response'] = 'Não encontramos o que deseja, sugerimos falar com um atendente. ';
																$this->saveQuestionNotFound($kw, $cad, $cod_usu);
															}
							
							
							
														}
							
													}else{
														$resp['status'] = "failed";
														$resp['response'] = 'Algo deu errado na conexão com nossas respostas!';
														$this->saveQuestionNotFound($kw, $cad, $cod_usu);
														$resp['msg'] = $db->conn->error;
													}
							
							
							
												}
		
												if(count($resultado['intents']) == 0 && count($resultado['entities']) > 0 ){
													$resp['status'] = 'success';
													// $resp['response'] = $this->settings->info('no_answer');
													$resp['response'] = '<p>Você poderia reformular a pergunta por favor?</p>';
													$this->saveQuestionNotFound($kw, $cad, $cod_usu);
												}
		
												if(count($resultado['intents']) > 0 && count($resultado['entities']) == 0 ){
													$resp['status'] = 'success';
													// $resp['response'] = $this->settings->info('no_answer');
													$resp['response'] = '<p>Você poderia reformular a pergunta por favor?</p>';
													$this->saveQuestionNotFound($kw, $cad, $cod_usu);
												}
		
		
											}
		
										}else{
											$resp['status'] = "failed";
											$resp['response'] = 'Algo deu errado na conexão com nossas respostas!';
											$this->saveQuestionNotFound($kw, $cad, $cod_usu);
											$resp['msg'] = $db->conn->error;
										}
									}
		
									if(count($resultado['traits']) >= 1){
		
										// echo "Confiança muito baixa";
										if(count($resultado['entities']) > 0 && count($resultado['intents']) > 0){
										
											$sqlWhereIn = " WHERE ";
		
											foreach($resultado['entities'] as $key => $value){
		
											$sqlWhereIn .= " ( ";	
					
											for ($i=1; $i <= 6; $i++) { 
					
												$sqlWhereIn .= " entity".$i." IN(";
					
												
													// // $key = explode(':', $key);
													// // echo "<pre>";
													// // print_r($key[0]);
													// // echo "</pre>";
													// echo "<pre>";
													// print_r($value[0]['name']);
													// echo "</pre>";
							
													$sqlWhereIn .= "'" . $value[0]['name'] . "',";
							
													
													$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-1);
													$sqlWhereIn .= ") OR";
												}
		
												$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
												$sqlWhereIn .= ") AND ";
												
											
											}
							
											
					
											// $sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
					
											$sqlWhereIn .= " intent IN('". $resultado['intents'][array_key_first($resultado['intents'])]['name'] ."') ";
											$sqlWhereIn .= "LIMIT 1";
										
											// echo "<pre>";
											// print_r($sqlWhereIn);
											// echo "</pre>";
											// return;
					
					
											$sql = "SELECT * FROM `chat_bot_response_list`" . $sqlWhereIn;
					
											// $sql = "SELECT response FROM `chat_bot_response_list` WHERE entity1 IN('email','criar') OR entity2 IN('email','criar') OR entity3 IN('email','criar') OR entity4 IN('email','criar') OR entity5 IN('email','criar') OR entity6 IN('email','criar') LIMIT 1";
					
											// return $sql;
								
											$qry = $db->conn->query($sql);
					
											if($qry){
					
												if($qry->num_rows > 0){
					
													$result = $qry->fetch_array();
													$resp['response'] = mb_convert_encoding($result['response'], 'UTF-8', 'ISO-8859-1');
													$sg_qry = $db->conn->query("SELECT suggestion FROM `chat_bot_suggestion_list` where response_id = '{$result['id']}'");
													// if($sg_qry->num_rows > 0){
													// 	$suggestions = array_column($sg_qry->fetch_all(MYSQLI_ASSOC), 'suggestion');
													// }else{
													// 	$suggestions = $this->settings->info('suggestion') != "" ? json_decode($this->settings->info('suggestion')) : "";
													// }
													// $resp['suggestions'] = mb_convert_encoding($suggestions, 'UTF-8', 'ISO-8859-1');
													if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
														$client = $_SERVER['HTTP_CLIENT_IP'];
													} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
														$client = $_SERVER['HTTP_X_FORWARDED_FOR'];
													} else {
														$client = $_SERVER['REMOTE_ADDR'];
													}
													$db->conn->query("INSERT INTO `chat_bot_keyword_fetched` set `response_id` = '{$result['id']}', `client` = '{$client}'");
												}else{
													$sqlWhereIn = " WHERE ";
		
													foreach($resultado['entities'] as $key => $value){
		
													$sqlWhereIn .= " ( ";	
							
													for ($i=1; $i <= 6; $i++) { 
							
														$sqlWhereIn .= " entity".$i." IN(";
							
														
															// // $key = explode(':', $key);
															// // echo "<pre>";
															// // print_r($key[0]);
															// // echo "</pre>";
															// echo "<pre>";
															// print_r($value[0]['name']);
															// echo "</pre>";
									
															$sqlWhereIn .= "'" . $value[0]['name'] . "',";
									
															
															$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-1);
															$sqlWhereIn .= ") OR";
														}
		
														$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
														$sqlWhereIn .= ") AND ";
														
													}
									
													
							
													$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-4);
							
													// $sqlWhereIn .= " intent IN('". $resultado['intents'][array_key_first($resultado['intents'])]['name'] ."') ";
													$sqlWhereIn .= " limit 5";
		
													$sql = "SELECT * FROM `chat_bot_response_list` " . $sqlWhereIn;
		
													// return $sql;
		
													$qry = $db->conn->query($sql);
		
													if($qry->num_rows > 0){
														
														
														$result = $qry->fetch_all(MYSQLI_ASSOC);
		
		
		
														$resp['response'] = "Veja se você quis dizer algumas dessas opções abaixo:";
		
							
														foreach($result as $q){
															$resp['suggestions'][] = $q['question'];
														}
													} else {
		
														if(count($resultado['intents']) > 0){
															$resp['status'] = 'success';
															// $resp['response'] = $this->settings->info('no_answer');
															$resp['response'] = 'Poderia ser mais específico em relação ao que você quer ' . $resultado['intents'][array_key_first($resultado['intents'])]['name'] . '?';
														}
		
														if(count($resultado['intents']) < 0){
															$resp['status'] = 'success';
															// $resp['response'] = $this->settings->info('no_answer');
															$resp['response'] = 'Não encontramos o que deseja, sugerimos falar com um atendente.';
														}
		
														$this->saveQuestionNotFound($kw, $cad, $cod_usu);
													}
					
					
					
												}
					
											}else{
												$resp['status'] = "failed";
												$resp['response'] = 'Algo deu errado na conexão com nossas respostas!';
												$resp['msg'] = $db->conn->error;
											}
					
					
					
										}
									}
		
								}
		
								if($confidenceComparacao >= 0.9){
		
										// $sql = "SELECT * FROM `chat_bot_response_list` WHERE `traits` = '".$resultado['entities'][array_key_first($resultado['entities'])][0]['name']."'";
										// $sql = "SELECT * FROM `chat_bot_response_list` where id in (SELECT response_id FROM `chat_bot_keyword_list` where `keyword` = '{$kw}')";
					
									$sql = "SELECT * FROM `chat_bot_response_list` WHERE `trait` = '". $traitMaior ."'";
		
									$qry = $db->conn->query($sql);
		
									if($qry){
		
										if($qry->num_rows > 0){
		
											$result = $qry->fetch_array();
											$resp['response'] = $result['response'];
											// $resp['response'] = mb_convert_encoding($result['response'], 'UTF-8', 'ISO-8859-1');
		
											// $sg_qry = $db->conn->query("SELECT suggestion FROM `chat_bot_suggestion_list` where response_id = '{$result['id']}'");
											// if($sg_qry->num_rows > 0){
											// 	$suggestions = array_column($sg_qry->fetch_all(MYSQLI_ASSOC), 'suggestion');
											// }else{
											// 	$suggestions = $this->settings->info('suggestion') != "" ? json_decode($this->settings->info('suggestion')) : "";
											// }
											// $resp['suggestions'] = $suggestions;
		
		
											if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
												$client = $_SERVER['HTTP_CLIENT_IP'];
											} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
												$client = $_SERVER['HTTP_X_FORWARDED_FOR'];
											} else {
												$client = $_SERVER['REMOTE_ADDR'];
											}
											$db->conn->query("INSERT INTO `chat_bot_keyword_fetched` set `response_id` = '{$result['id']}', `client` = '{$client}'");
										}else{
		
											if(count($resultado['entities']) > 0 && count($resultado['intents']) > 0){
									
												$sqlWhereIn = " WHERE ";
					
												foreach($resultado['entities'] as $key => $value){
					
												$sqlWhereIn .= " ( ";	
						
												for ($i=1; $i <= 6; $i++) { 
						
													$sqlWhereIn .= " entity".$i." IN(";
						
													
														// // $key = explode(':', $key);
														// // echo "<pre>";
														// // print_r($key[0]);
														// // echo "</pre>";
														// echo "<pre>";
														// print_r($value[0]['name']);
														// echo "</pre>";
								
														$sqlWhereIn .= "'" . $value[0]['name'] . "',";
								
														
														$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-1);
														$sqlWhereIn .= ") OR";
													}
					
													$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
													$sqlWhereIn .= ") AND ";
													
												}
								
												
						
												// $sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
						
												$sqlWhereIn .= " intent IN('". $resultado['intents'][array_key_first($resultado['intents'])]['name'] ."') ";
												$sqlWhereIn .= "LIMIT 1";
											
												// echo "<pre>";
												// print_r($sqlWhereIn);
												// echo "</pre>";
												// return;
						
						
												$sql = "SELECT * FROM `chat_bot_response_list`" . $sqlWhereIn;
						
												// $sql = "SELECT response FROM `chat_bot_response_list` WHERE entity1 IN('email','criar') OR entity2 IN('email','criar') OR entity3 IN('email','criar') OR entity4 IN('email','criar') OR entity5 IN('email','criar') OR entity6 IN('email','criar') LIMIT 1";
						
												// return $sql;
									
												$qry = $db->conn->query($sql);
						
												if($qry){
						
													if($qry->num_rows > 0){
						
														$result = $qry->fetch_array();
														$resp['response'] = mb_convert_encoding($result['response'], 'UTF-8', 'ISO-8859-1');
														// $sg_qry = $db->conn->query("SELECT suggestion FROM `chat_bot_suggestion_list` where response_id = '{$result['id']}'");
														// if($sg_qry->num_rows > 0){
														// 	$suggestions = array_column($sg_qry->fetch_all(MYSQLI_ASSOC), 'suggestion');
														// }else{
														// 	$suggestions = $this->settings->info('suggestion') != "" ? json_decode($this->settings->info('suggestion')) : "";
														// }
														// $resp['suggestions'] = mb_convert_encoding($suggestions, 'UTF-8', 'ISO-8859-1');
														if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
															$client = $_SERVER['HTTP_CLIENT_IP'];
														} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
															$client = $_SERVER['HTTP_X_FORWARDED_FOR'];
														} else {
															$client = $_SERVER['REMOTE_ADDR'];
														}
														$db->conn->query("INSERT INTO `chat_bot_keyword_fetched` set `response_id` = '{$result['id']}', `client` = '{$client}'");
													}else{
						
														if(count($resultado['intents']) > 0){
															$resp['status'] = 'success';
															// $resp['response'] = $this->settings->info('no_answer');
															$resp['response'] = 'Poderia ser mais específico em relação ao que você quer ' . $resultado['intents'][array_key_first($resultado['intents'])]['name'] . '?';
															$this->saveQuestionNotFound($kw, $cad, $cod_usu);
														}
						
														if(count($resultado['intents']) < 0){
															$resp['status'] = 'success';
															// $resp['response'] = $this->settings->info('no_answer');
															$resp['response'] = 'Não encontramos o que deseja, sugerimos falar com um atendente. ';
															$this->saveQuestionNotFound($kw, $cad, $cod_usu);
														}
						
						
						
													}
						
												}else{
													$resp['status'] = "failed";
													$resp['response'] = 'Algo deu errado na conexão com nossas respostas!';
													$this->saveQuestionNotFound($kw, $cad, $cod_usu);
													$resp['msg'] = $db->conn->error;
												}
						
						
						
											}
		
											if(count($resultado['intents']) == 0 && count($resultado['entities']) > 0 ){
												$resp['status'] = 'success';
												// $resp['response'] = $this->settings->info('no_answer');
												$resp['response'] = '<p>Você poderia reformular a pergunta por favor?</p>';
												$this->saveQuestionNotFound($kw, $cad, $cod_usu);
											}
		
											if(count($resultado['intents']) > 0 && count($resultado['entities']) == 0 ){
												$resp['status'] = 'success';
												// $resp['response'] = $this->settings->info('no_answer');
												$resp['response'] = '<p>Você poderia reformular a pergunta por favor?</p>';
												$this->saveQuestionNotFound($kw, $cad, $cod_usu);
											}
		
		
										}
		
									}else{
										$resp['status'] = "failed";
										$resp['response'] = 'Algo deu errado na conexão com nossas respostas!';
										$this->saveQuestionNotFound($kw, $cad, $cod_usu);
										$resp['msg'] = $db->conn->error;
									}
		
								
								}
								// echo "<pre>";
								// print_r($resultado['traits'][array_key_first($resultado['traits'])][0]['value']);
								// print_r($resultado['traits'][array_key_first($resultado['traits'])][0]['confidence']);
								// echo "</pre>";
								
								
							
						}
		
		
						if(count($resultado['traits']) == 0){
							if(count($resultado['entities']) > 0 && count($resultado['intents']) > 0){
								
								$sqlWhereIn = " WHERE ";
		
									foreach($resultado['entities'] as $key => $value){
		
									$sqlWhereIn .= " ( ";	
			
									for ($i=1; $i <= 6; $i++) { 
			
										$sqlWhereIn .= " entity".$i." IN(";
			
										
											// // $key = explode(':', $key);
											// // echo "<pre>";
											// // print_r($key[0]);
											// // echo "</pre>";
											// echo "<pre>";
											// print_r($value[0]['name']);
											// echo "</pre>";
					
											$sqlWhereIn .= "'" . $value[0]['name'] . "',";
					
											
											$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-1);
											$sqlWhereIn .= ") OR";
										}
		
										$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
										$sqlWhereIn .= ") AND ";
										
									}
					
									
			
									// $sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
			
									$sqlWhereIn .= " intent IN('". $resultado['intents'][array_key_first($resultado['intents'])]['name'] ."') ";
									$sqlWhereIn .= "limit 5";
							
								// echo "<pre>";
								// print_r($sqlWhereIn);
								// echo "</pre>";
								// return;
		
		
								$sql = "SELECT * FROM `chat_bot_response_list`" . $sqlWhereIn;
		
								// $sql = "SELECT response FROM `chat_bot_response_list` WHERE entity1 IN('email','criar') OR entity2 IN('email','criar') OR entity3 IN('email','criar') OR entity4 IN('email','criar') OR entity5 IN('email','criar') OR entity6 IN('email','criar') LIMIT 1";
		
								// return $sql;
					
								$qry = $db->conn->query($sql);
		
								
		
								if($qry){
		
									if($qry->num_rows > 0){
		
										$result = $qry->fetch_all(MYSQLI_ASSOC);
		
										// $result = $qry->fetch_array();
		
		
										$resp['response'] = "Não entendi perfeitamente o que quis dizer, clique na opção que mais se encaixa na sua dúvida.";
		
		
										foreach($result as $q){
											$resp['suggestions'][] = $q['question'];
										}
		
										// $resp['response'] = mb_convert_encoding($result['response'], 'UTF-8', 'ISO-8859-1');
										// $sg_qry = $db->conn->query("SELECT suggestion FROM `chat_bot_suggestion_list` where response_id = '{$result['id']}'");
										// if($sg_qry->num_rows > 0){
										// 	$suggestions = array_column($sg_qry->fetch_all(MYSQLI_ASSOC), 'suggestion');
										// }else{
										// 	$suggestions = $this->settings->info('suggestion') != "" ? json_decode($this->settings->info('suggestion')) : "";
										// }
										// $resp['suggestions'] = mb_convert_encoding($suggestions, 'UTF-8', 'ISO-8859-1');
		
		
		
										// if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
										// 	$client = $_SERVER['HTTP_CLIENT_IP'];
										// } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
										// 	$client = $_SERVER['HTTP_X_FORWARDED_FOR'];
										// } else {
										// 	$client = $_SERVER['REMOTE_ADDR'];
										// }
										// $db->conn->query("INSERT INTO `chat_bot_keyword_fetched` set `response_id` = '{$result['id']}', `client` = '{$client}'");
									}else{
		
										if(count($resultado['intents']) > 0){
											$resp['status'] = 'success';
											// $resp['response'] = $this->settings->info('no_answer');
											$resp['response'] = 'Poderia ser mais específico em relação ao que você quer ' . $resultado['intents'][array_key_first($resultado['intents'])]['name'] . '?';
											$this->saveQuestionNotFound($kw, $cad, $cod_usu);
										}
		
										if(count($resultado['intents']) < 0){
											$resp['status'] = 'success';
											// $resp['response'] = $this->settings->info('no_answer');
											$resp['response'] = 'Não encontramos o que deseja, sugerimos falar com um atendente.';
											$this->saveQuestionNotFound($kw, $cad, $cod_usu);
										}
		
		
		
									}
		
								}else{
									$resp['status'] = "failed";
									$resp['response'] = 'Algo deu errado na conexão com nossas respostas!';
									$this->saveQuestionNotFound($kw, $cad, $cod_usu);
									$resp['msg'] = $db->conn->error;
								}
		
							}
		
							if(count($resultado['entities']) > 0 && count($resultado['intents']) == 0){
								
								$sqlWhereIn = " WHERE ";
		
									foreach($resultado['entities'] as $key => $value){
		
									$sqlWhereIn .= " ( ";	
			
									for ($i=1; $i <= 6; $i++) { 
			
										$sqlWhereIn .= " entity".$i." IN(";
			
										
											// // $key = explode(':', $key);
											// // echo "<pre>";
											// // print_r($key[0]);
											// // echo "</pre>";
											// echo "<pre>";
											// print_r($value[0]['name']);
											// echo "</pre>";
					
											$sqlWhereIn .= "'" . $value[0]['name'] . "',";
					
											
											$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-1);
											$sqlWhereIn .= ") OR";
										}
		
										$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
										$sqlWhereIn .= ") AND ";
										
									}
					
									
			
									$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-4);
			
									// $sqlWhereIn .= " intent IN('". $resultado['intents'][array_key_first($resultado['intents'])]['name'] ."') ";
									$sqlWhereIn .= "limit 5";
							
								// echo "<pre>";
								// print_r($sqlWhereIn);
								// echo "</pre>";
								// return;
		
		
								$sql = "SELECT * FROM `chat_bot_response_list`" . $sqlWhereIn;
		
								// $sql = "SELECT response FROM `chat_bot_response_list` WHERE entity1 IN('email','criar') OR entity2 IN('email','criar') OR entity3 IN('email','criar') OR entity4 IN('email','criar') OR entity5 IN('email','criar') OR entity6 IN('email','criar') LIMIT 1";
		
								// return $sql;
					
								$qry = $db->conn->query($sql);
		
								if($qry){
		
									if($qry->num_rows > 0){
		
										// $result = $qry->fetch_array();
										$result = $qry->fetch_all(MYSQLI_ASSOC);
		
		
		
										$resp['response'] = "Veja se você quis dizer algumas dessas opções abaixo:";
		
										// $suggestions = array_column($qry->fetch_all(MYSQLI_ASSOC), 'question');
		
										// $sg_qry = $db->conn->query("SELECT suggestion FROM `chat_bot_suggestion_list` where response_id = '{$result['id']}'");
										// if($sg_qry->num_rows > 0){
										// 	$suggestions = array_column($sg_qry->fetch_all(MYSQLI_ASSOC), 'suggestion');
										// }else{
										// 	$suggestions = $this->settings->info('suggestion') != "" ? json_decode($this->settings->info('suggestion')) : "";
										// }
										foreach($result as $q){
											$resp['suggestions'][] = $q['question'];
										}
										// $resp['suggestions'] = $suggestions;
		
										// return $resp;
		
										// $resp['suggestions'] = mb_convert_encoding($suggestions, 'UTF-8', 'ISO-8859-1');
		
										if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
											$client = $_SERVER['HTTP_CLIENT_IP'];
										} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
											$client = $_SERVER['HTTP_X_FORWARDED_FOR'];
										} else {
											$client = $_SERVER['REMOTE_ADDR'];
										}
										// $db->conn->query("INSERT INTO `chat_bot_keyword_fetched` set `response_id` = '{$result['id']}', `client` = '{$client}'");
									}else{
		
		
											$sqlWhereIn = " WHERE ";
		
											foreach($resultado['entities'] as $key => $value){
		
											$sqlWhereIn .= " ( ";	
					
											for ($i=1; $i <= 6; $i++) { 
					
												$sqlWhereIn .= " entity".$i." IN(";
					
												
													// // $key = explode(':', $key);
													// // echo "<pre>";
													// // print_r($key[0]);
													// // echo "</pre>";
													// echo "<pre>";
													// print_r($value[0]['name']);
													// echo "</pre>";
							
													$sqlWhereIn .= "'" . $value[0]['name'] . "',";
							
													
													$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-1);
													$sqlWhereIn .= ") OR";
												}
		
												$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-2);
												$sqlWhereIn .= ") AND ";
												
											}
							
											
					
											$sqlWhereIn = substr($sqlWhereIn, 0, strlen($sqlWhereIn)-4);
					
											// $sqlWhereIn .= " intent IN('". $resultado['intents'][array_key_first($resultado['intents'])]['name'] ."') ";
											$sqlWhereIn .= " limit 5";
		
											$sql = "SELECT * FROM `chat_bot_response_list` " . $sqlWhereIn;
		
											// return $sql;
		
											$qry = $db->conn->query($sql);
		
											if($qry->num_rows > 0){
												
												$result = $qry->fetch_all(MYSQLI_ASSOC);
		
		
		
												$resp['response'] = "Veja se você quis dizer algumas dessas opções abaixo:";
		
												// $suggestions = array_column($qry->fetch_all(MYSQLI_ASSOC), 'question');
		
												// $sg_qry = $db->conn->query("SELECT suggestion FROM `chat_bot_suggestion_list` where response_id = '{$result['id']}'");
												// if($sg_qry->num_rows > 0){
												// 	$suggestions = array_column($sg_qry->fetch_all(MYSQLI_ASSOC), 'suggestion');
												// }else{
												// 	$suggestions = $this->settings->info('suggestion') != "" ? json_decode($this->settings->info('suggestion')) : "";
												// }
												foreach($result as $q){
													$resp['suggestions'][] = $q['question'];
												}
											} else {
		
												$resp['status'] = 'success';
												// $resp['response'] = $this->settings->info('no_answer');
												$resp['response'] = 'Não encontramos o que deseja, sugerimos falar com um atendente.';
												$this->saveQuestionNotFound($kw, $cad, $cod_usu);
		
											}
		
											
									}
		
								}else{
									$resp['status'] = "failed";
									$resp['response'] = 'Algo deu errado na conexão com nossas respostas!';
									$this->saveQuestionNotFound($kw, $cad, $cod_usu);
									$resp['msg'] = $db->conn->error;
								}
		
		
		
							}
		
							if(count($resultado['entities']) == 0 && count($resultado['intents']) == 0){
								
							}
		
							if(count($resultado['entities']) == 0 && count($resultado['intents']) > 0){
								$resp['status'] = 'success';
								// $resp['response'] = $this->settings->info('no_answer');
								$resp['response'] = 'Poderia ser mais específico em relação ao que você quer ' . $resultado['intents'][array_key_first($resultado['intents'])]['name'] . '?';
								$this->saveQuestionNotFound($kw, $cad, $cod_usu);
							}
						}
		
					
					} else if(is_countable($resultado['entities']) && count($resultado['traits']) == 0){

						$sql = "SELECT * FROM `chat_bot_response_list` WHERE `question` LIKE '%". trim($resultado['text']) ."%' LIMIT 4";

						$qry = $db->conn->query($sql);

						if($qry){

							if($qry->num_rows > 0){

								$result = $qry->fetch_all(MYSQLI_ASSOC);
							
								$resp['response'] = "Não entendi perfeitamente o que quis dizer, clique na opção que mais se encaixa na sua dúvida.";
				
								foreach($result as $q){
									$resp['suggestions'][] = $q['question'];
								}

								$this->saveQuestionNotFound($kw, $cad, $cod_usu);

							} else{
								$resp['response'] = "Poderia ser mais específico em relação ào que gostaria de saber ?";
								$this->saveQuestionNotFound($kw, $cad, $cod_usu);
							}
						}
						
					} else if(is_countable($resultado['intent'])){
						$sql = "SELECT * FROM `chat_bot_response_list` WHERE `question` LIKE '%". trim($resultado['text']) ."%' LIMIT 4";

						$qry = $db->conn->query($sql);

						if($qry){

							if($qry->num_rows > 0){

								$result = $qry->fetch_all(MYSQLI_ASSOC);
							
								$resp['response'] = "Não entendi perfeitamente o que quis dizer, clique na opção que mais se encaixa na sua dúvida.";
				
								foreach($result as $q){
									$resp['suggestions'][] = $q['question'];
								}

								$this->saveQuestionNotFound($kw, $cad, $cod_usu);

							} else{
								$resp['response'] = "Poderia ser mais específico em relação à sua intenção ?";
								$this->saveQuestionNotFound($kw, $cad, $cod_usu);
							}
						}
					}

				}

			}else{
				$resp['status'] = "failed";
				$resp['response'] = 'Algo deu errado na conexão com nossas respostas!';
				$this->saveQuestionNotFound($kw, $cad, $cod_usu);
				$resp['msg'] = $db->conn->error;
			}


			$resp['traits'] = $countTraits;
			$resp['entities'] = $countEntities;

			// teste resp
			// echo "<pre>";
			// print_r($resp);
			// echo "</pre>";
	


	
			// echo $resp;
			// return;
			// echo utf8ize($resp);
			return $resp;
			// return json_encode($resp);
		}

		public function saveQuestionNotFound($question, $cod_cad, $cod_usu){

			$db = new DbConnect();
			$db->conn->query("INSERT INTO `chat_bot_question_not_found_list` set `question` = '{$question}', `cod_cad` = '{$cod_cad}', `cod_usu` = '{$cod_usu}'");
		}
	}

	


	
	
 ?>