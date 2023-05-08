<?php 

	require_once('SystemSettings.php');

	class Api extends Rest {

		private $settings;
		
		public function __construct() {
			global $_settings;
			$this->settings = $_settings;
			parent::__construct();
		}

		public function generateToken() {
			$email = $this->validateParameter('email', $this->param['email'], STRING);
			$pass = $this->validateParameter('pass', $this->param['pass'], STRING);
			try {
				$stmt = $this->dbConn->prepare("SELECT * FROM chat_bot_users WHERE email = :email AND password = :pass");
				$stmt->bindParam(":email", $email);
				$stmt->bindParam(":pass", $pass);
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

			$resp = new Response;
			$kw = $this->validateParameter('kw', $this->param['kw'], STRING, true);



			// $response = $resp->getResponse();
			
			// if(!is_array($response)) {
				// 	$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'response details not found.']);
				// }

			$response = $this->fetch_response($kw);
				
			$this->returnResponse(SUCCESS_RESPONSE, $response);
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

		public function fetch_response($kw){
			// extract($_POST);
	
			$token = 'RSFVNKM4SDC67NDVPTM7VOAEK576BOXQ';
	
	
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
			
				
			
			$db = new DbConnect();
	
			$sql = "SELECT * FROM `chat_bot_response_list` WHERE `entity` = '".$resultado['entities'][array_key_first($resultado['entities'])][0]['name']."'";
			// // $sql = "SELECT * FROM `chat_bot_response_list` where id in (SELECT response_id FROM `chat_bot_keyword_list` where `keyword` = '{$kw}')";
	
			
	
			$qry = $db->conn->query($sql);
			if($qry){
				if($qry->num_rows > 0){
					$result = $qry->fetch_array();
					$resp['response'] = mb_convert_encoding($result['response'], 'UTF-8', 'ISO-8859-1');
					$sg_qry = $db->conn->query("SELECT suggestion FROM `chat_bot_suggestion_list` where response_id = '{$result['id']}'");
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
					$db->conn->query("INSERT INTO `chat_bot_keyword_fetched` set `response_id` = '{$result['id']}', `client` = '{$client}'");
				}else{
					$resp['status'] = 'success';
					// $resp['response'] = $this->settings->info('no_answer');
					$resp['response'] = 'Sem respostas';
				}
			}else{
				$resp['status'] = "failed";
				$resp['msg'] = $db->conn->error;
			}
	
	
			// teste resp
			// echo "<pre>";
			// print_r($resp);
			// echo "</pre>";
	
	
			return $resp;
			// return json_encode($resp);
		}
	}


	
	
 ?>