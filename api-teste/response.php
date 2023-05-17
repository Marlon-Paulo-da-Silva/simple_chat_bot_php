<?php 
	class Response {
		private $id;
		private $response;
		private $status;
		private $entity;
		private $date_created;
		private $date_updated;
		private $tableName = 'chat_bot_response_list';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setResponse($response) { $this->response = $response; }
		function getResponse() { return $this->response; }
		function setStatus($status) { $this->status = $status; }
		function getStatus() { return $this->status; }
		function setEntity($entity) { $this->entity = $entity; }
		function getEntity() { return $this->entity; }
		function setDateUpdated($date_updated) { $this->date_updated = $date_updated; }
		function getDateUpdated() { return $this->date_updated; }
		function setDateCreated($date_created) { $this->date_created = $date_created; }
		function getDateCreated() { return $this->date_created; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->conn;
		}

		public function getAllResponses() {
			$stmt = $this->dbConn->query("SELECT * FROM " . $this->tableName);
			$responses = $stmt->fetch_array();
			return $responses;
		}

	}
 ?>