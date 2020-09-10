<?php
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');

	include_once '../libs/php-jwt-master/src/BeforeValidException.php';
	include_once '../libs/php-jwt-master/src/ExpiredException.php';
	include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
	include_once '../libs/php-jwt-master/src/JWT.php';
	include_once '../config/jwt_config.php';
	use \Firebase\JWT\JWT;

	include_once '../connection.php';
	include_once '../models/works.php';

	$db = new Database();
	$conn = $db->connect();
	$works= new works();
	$works->connection = $conn;

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		if(isset($_GET['user_data'])isset($_GET['business_ruc'])){
			$works->user_data = isset($_GET['user_data']) ? $_GET['user_data'] : '';$works->business_ruc = isset($_GET['business_ruc']) ? $_GET['business_ruc'] : '';
			$stmt = $works->findOne();

			if($stmt->columnCount() > 0){
				if($row = $stmt->fetch()){
					$item = array(
                        'user_data' => $row['user_data'],
                        'business_ruc' => $row['business_ruc'],
                        'role' => $row['role']
				    );
					header('HTTP/1.1 200 OK', true, 200);
					echo json_encode($item);
					return;
				}
			}else{
				header('HTTP/1.1 204 No Content', true, 204);
				echo json_encode( array('message' => 'NO ROWS FOUND') );
				return;
			}
		}else{
			$stmt = $works->findAll();

			if($stmt->columnCount() > 0){
				$list = array();

				while($row = $stmt->fetch()){
					$item = array(
                        'user_data' => $row['user_data'],
                        'business_ruc' => $row['business_ruc'],
                        'role' => $row['role']
					);
					array_push($list, $item);
				}
				header('HTTP/1.1 200 OK', true, 200);
				echo json_encode($list);
				return;
			}else{
				header('HTTP/1.1 204 No Content', true, 204);
				echo json_encode( array('message' => 'NO ROWS FOUND') );
				return;
			}
		}

	}else {
		$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
		if(strcasecmp($contentType, 'application/json') != 0){
			header('HTTP/1.1 404 Bad Request', true, 404);//throw new Exception('Content type must be: application/json');
			return;
		}		

		$bodydata = trim(file_get_contents('php://input'));
		$jsondata = json_decode($bodydata, true);

		if(!is_array($jsondata)){
			header('HTTP/1.1 404 Bad Request');
		}

		/*$jwt = isset($jsondata['token']) ? $jsondata['token'] : '';
		if($jwt){
			try {
				$decoded = JWT::decode($jwt, $key, array('HS256'));
			}	catch (Exception $e){
				header('HTTP/1.1 401 Unauthorized', true, 401);
				echo json_encode(array(
					'message' => 'Access denied.',
					'error' => $e->getMessage()
				));
				return;
			}

		}*/

		$works->user_data = isset($jsondata['user_data']) ? utf8_decode($jsondata['user_data']) : '';
		$works->business_ruc = isset($jsondata['business_ruc']) ? utf8_decode($jsondata['business_ruc']) : '';
		$works->role = isset($jsondata['role']) ? utf8_decode($jsondata['role']) : '';
		
		if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
			if( $works->delete() ){
				header('HTTP/1.1 200 OK', true, 200);
				echo json_encode( array('message' => 'SUCCESSFUL') );
				return;
			}else{
				header('HTTP/1.1 202 Accepted', true, 202);
				echo json_encode( array('message' => 'ERROR WHILE DELETE') );
			}
		}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if( $works->insert() ){
				header('HTTP/1.1 201 Created', true, 201);
				echo json_encode( array('message' => 'SUCCESSFUL') );
				return;
			}else{
				header('HTTP/1.1 202 Accepted', true, 202);
				echo json_encode( array('message' => 'ERROR WHILE INSERT') );
			}
		}else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
			if( $works->update() ){
				header('HTTP/1.1 200 OK', true, 200);
				echo json_encode( array('message' => 'SUCCESSFUL') );
				return;
			}else{
				header('HTTP/1.1 202 Accepted', true, 202);
				echo json_encode( array('message' => 'ERROR WHILE UPDATE') );
			}
		}
	}
?>