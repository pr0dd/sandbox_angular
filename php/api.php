<?php

 	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";
		
		const DB_SERVER = "127.0.0.1";
		const DB_USER = "root";
		const DB_PASSWORD = "";
		const DB = "sandbox";

		private $db = NULL;
		private $_siteEmail = "sgx4321@gmail.com";
		private $mysqli = NULL;
		public function __construct(){
			// parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Connect to Database
		*/
		private function dbConnect(){
			$this->mysqli = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
			mysqli_set_charset($this->mysqli, "utf8mb4");
		}
		
		/*
		 * Dynmically call the method based on the query string
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['x'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404); // If the method not exist with in this class "Page not found".
		}

		private function updateimgs($folder, $table, $id = null){
			$upImageFiles = new DirectoryIterator($folder);
			$upImagesArray = array();
			while ($upImageFiles->valid()) {
			    $upFile = $upImageFiles->current();
			    $upFileName = $upFile->getFilename();
			    $upSrc = "$folder/$upFileName";
			    $upFileInfo = new Finfo(FILEINFO_MIME_TYPE);
			    $mimeType = $upFileInfo->file($upSrc);
				if($mimeType === "image/jpeg"){
			    	$upImagesArray[] = substr($upSrc, 3);
			    }
			    $upImageFiles->next();
			}
			//UPDATE DB:
			$update = implode(',', $upImagesArray);
			if($id !== null) {
				// FOR PRODUCT IMAGES UPDATES:
				$query = "UPDATE $table SET src = '$update' WHERE productId = $id";
			} else {
				//FOR STARTPAGE OR CATEGORY OR PROMOTIONAL IMGS UPDATES:
				$query = "UPDATE $table SET src = '$update' WHERE id = 1";
			}
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			$this->response(json_encode("all good"), 200);
		}

		// =============== NOT REQUIRED YET ===============

		private function addAvail(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$filename = $_FILES['file']['name'];
			$fileData = $_FILES['file']['tmp_name'];
			//MOVE 1 LVL UP AND CREATE DESTINATION PATH:
			$destination = "../" . $_POST['path'] ."/". $filename;

			//CHECK IF FILE WITH SAME NAME IS ALREADY INSIDE THE FOLDER:
			if( file_exists($destination) ){
				$this->response(json_encode("already exists"), 406);
			}
			//CHECK IF THE UPLOADED FILE IS AN ACTUAL IMAGE:
			$check = getimagesize($fileData);
			if(!$check) {
				$this->response('',406);
				// $this->response(json_encode("not an image"),406);
			}
			//CHECK EXTENTION, IN CASE JS VALIDATIONS SOMEHOW HAD FAILED:
			$imageFileType = pathinfo($destination, PATHINFO_EXTENSION);
			$imgStr = strtolower($imageFileType);
			if($imgStr != "jpg" && $imgStr != "jpeg") {
				$this->response('',406);
				// $this->response(json_encode("wrong format"),406);
			}
			//MOVE THE UPLOADED FILE:
			$success = move_uploaded_file( $fileData , $destination );

			if($success){
				$folder = "../" . $_POST['path'];
				$table = $_POST['table'];
				if( !empty($_POST['productId']) ){
					$id = $_POST['productId'];
					$this->updateimgs($folder,$table,$id);
				} else {
					$this->updateimgs($folder,$table);
				}
						
			} else {
				$this->response('',406);
			}
		}


		// ==== GET START PAGE IMAGES ====
		private function getSPI(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT DISTINCT 
					s.id, 
					s.src, 
					s.catId 
					FROM startpageimages AS s";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$spi = array();
				while($row = $r->fetch_assoc()){
					$row['id'] = (int)$row['id'];
					$row['catId'] = (int)$row['catId'];
					$spi[] = $row;
				}
			    $this->response(json_encode($spi), 200);
			} else {
			    $this->response('',204);
			}
		}

		// ==== GET AVAILABLE IMAGES FOR START PAGE ====
		private function getAvailSPI(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT av.src FROM availspi AS av WHERE id=1";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$avail = $r->fetch_assoc();

				if( (bool)$avail['src'] ){
					//If it is NOT NULL split it to array:
					$avail['src'] = explode(",", $avail['src']);
					//trim each array element:
					for( $i=0; $i<count($avail['src']); $i++) {
						$avail['src'][$i] = trim($avail['src'][$i]);
					}
				} else {
					//If it's NULL then send back an empty array:
					$avail['src'] = array();
				}
			    $this->response(json_encode($avail['src']), 200);
			} else {
			    $this->response('',204);
			}
		}

		// ==== STARTPAGE IMAGES: UPDATE ====
		private function updateSPI(){
			if($this->get_request_method() != "PUT"){
				$this->response('',406);
			}

			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can't create anything;
						break;
					case 'admin':
						//admins are allowed:
						$proceed = true;
						break;
					case 'super':
						//can create everything:
						$proceed = true;
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {
					$query = "UPDATE startpageimages SET 
						src = ?, 
						catId = ?					
						WHERE id = ?";
					
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("sss", 
						$userData['src'], 
						$userData['catId'], 
						$userData['id']
					);
					$stmt->execute() or die($this->mysqli->error.__LINE__);
					$stmt->close();
					$this->response(json_encode("update successful, spi with ID:".$userData['id']), 200);
				} else {
					$this->response(json_encode("Can't update"), 406);
				}
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		// ==== GET AVAILABLE IMAGES FOR CATEGORIES ====
		private function getAvailCTI(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT av.src FROM availcti AS av WHERE id=1";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$avail = $r->fetch_assoc();

				if( (bool)$avail['src'] ){
					//If it is NOT NULL split it to array:
					$avail['src'] = explode(",", $avail['src']);
					//trim each array element:
					for( $i=0; $i<count($avail['src']); $i++) {
						$avail['src'][$i] = trim($avail['src'][$i]);
					}
				} else {
					//If it's NULL then send back an empty array:
					$avail['src'] = array();
				}
			    $this->response(json_encode($avail['src']), 200);
			} else {
			    $this->response('',204);
			}
		}

		// ==== GET AVAILABLE IMAGES FOR PROMOTIONS ====
		private function getAvailPROMI(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT av.src FROM availpromi AS av WHERE id=1";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$avail = $r->fetch_assoc();

				if( (bool)$avail['src'] ){
					//If it is NOT NULL split it to array:
					$avail['src'] = explode(",", $avail['src']);
					//trim each array element:
					for( $i=0; $i<count($avail['src']); $i++) {
						$avail['src'][$i] = trim($avail['src'][$i]);
					}
				} else {
					//If it's NULL then send back an empty array:
					$avail['src'] = array();
				}
			    $this->response(json_encode($avail['src']), 200);
			} else {
			    $this->response('',204);
			}
		}

		// ==== GET AVAILABLE IMAGES FOR PRODUCTS ====
		private function getAvailPRI(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT av.productId, av.src FROM availpri AS av";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$avail = array();
				while($row = $r->fetch_assoc()){
					if( (bool)$row['src'] ){
						//If it is NOT NULL split it to array:
						$row['src'] = explode(",", $row['src']);
						//trim each array element:
						for( $i=0; $i<count($row['src']); $i++) {
							$row['src'][$i] = trim($row['src'][$i]);
						}
					} else {
						//If it's NULL then send back an empty array:
						$row['src'] = array();
					}
					$row['productId'] = (int)$row['productId'];
					$avail[] = $row;
				}
			    $this->response(json_encode($avail), 200);
			} else {
			    $this->response('',204);
			}
		}

		// ==== GET PRODUCTS ====
		private function getProducts(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT DISTINCT 
					p.id,
					p.approved, 
					p.name, 
					p.ukrName, 
					p.price, 
					p.catId, 
					p.catRu, 
					p.catUkr, 
					p.catEn, 
					p.brand, 
					p.descrRu, 
					p.descrUkr, 
					p.color, 
					p.views, 
					p.inStock, 
					p.date, 
					p.images, 
					p.videos, 
					p.tech, 
					p.techUkr, 
					p.parts, 
					p.partsUkr 
					FROM products AS p";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$products = array();
				while($row = $r->fetch_assoc()){
					$row['id'] = (int)$row['id'];
					$row['price'] = (float)$row['price'];
					$row['catId'] = (int)$row['catId'];
					$row['views'] = (int)$row['views'];
					$row['inStock'] = (int)$row['inStock'];
					$row['date'] = (float)$row['date'];
					//CHECK IMAGES:
					if( (bool)$row['images'] ){
						//If it is NOT NULL split it to array:
						$row['images'] = explode(",", $row['images']);
						//trim each array element:
						for( $i=0; $i<count($row['images']); $i++) {
							$row['images'][$i] = trim($row['images'][$i]);
						}
					} else {
						//If it's NULL then send back an empty array:
						$row['images'] = array();
					}
					//CHECK VIDEOS:
					if( (bool)$row['videos'] ){
						//If it is NOT NULL split it to array:
						$row['videos'] = explode(",", $row['videos']);
						//trim each array element:
						for( $i=0; $i<count($row['videos']); $i++) {
							$row['videos'][$i] = trim($row['videos'][$i]);
						}
					} else {
						//If it's NULL then send back an empty array:
						$row['videos'] = array();
					}
					//CHECK TECH RU DETAILS:
					if( (bool)$row['tech'] ){
						//If it is NOT NULL split it to array:
						$row['tech'] = explode(",", $row['tech']);
						$tech = array();
						for( $i=0; $i<count($row['tech']); $i++) {
							//create new object:
							$tElem = new StdClass();
							$tElem->id = $i;
							$tElem->descr = trim($row['tech'][$i]);
							$tech[] = $tElem;
						}
						$row['tech'] = $tech;
					} else {
						//If it's NULL then send back an empty array:
						$row['tech'] = array();
					}
					//CHECK TECH UKR DETAILS:
					if( (bool)$row['techUkr'] ){
						//If it is NOT NULL split it to array:
						$row['techUkr'] = explode(",", $row['techUkr']);
						$techUkr = array();
						for( $i=0; $i<count($row['techUkr']); $i++) {
							//create new object:
							$tuElem = new StdClass();
							$tuElem->id = $i;
							$tuElem->descr = trim($row['techUkr'][$i]);
							$techUkr[] = $tuElem;
						}
						$row['techUkr'] = $techUkr;
					} else {
						//If it's NULL then send back an empty array:
						$row['techUkr'] = array();
					}
					//CHECK PARTS RU DESCRIPTION:
					if( (bool)$row['parts'] ){
						//If it is NOT NULL split it to array:
						$row['parts'] = explode(",", $row['parts']);
						$parts = array();
						for( $i=0; $i<count($row['parts']); $i++) {
							//create new object:
							$pElem = new StdClass();
							$pElem->id = $i;
							$pElem->descr = trim($row['parts'][$i]);
							$parts[] = $pElem;
						}
						$row['parts'] = $parts;
					} else {
						//If it's NULL then send back an empty array:
						$row['parts'] = array();
					}
					//CHECK PARTS UKR DESCRIPTION:
					if( (bool)$row['partsUkr'] ){
						//If it is NOT NULL split it to array:
						$row['partsUkr'] = explode(",", $row['partsUkr']);
						$partsUkr = array();
						for( $i=0; $i<count($row['partsUkr']); $i++) {
							//create new object:
							$puElem = new StdClass();
							$puElem->id = $i;
							$puElem->descr = trim($row['partsUkr'][$i]);
							$partsUkr[] = $puElem;
						}
						$row['partsUkr'] = $partsUkr;
					} else {
						//If it's NULL then send back an empty array:
						$row['partsUkr'] = array();
					}
					
					$products[] = $row;
				}
			    $this->response(json_encode($products), 200);
			} else {
			    $this->response('',204);
			}
		}


		// ==== PRODUCT: ADD ====
		private function addProduct(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can't create anything;
						break;
					case 'admin':
						//admins are allowed:
						$proceed = true;
						break;
					case 'super':
						//can create everything:
						$proceed = true;
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {
					//CREATE NEW PRODUCT:
					$query = "INSERT INTO products 
						(	`name`,
							`ukrName`,
							`price`,
							`catId`,
							`catRu`,
							`catUkr`,
							`catEn`,
							`brand`,
							`inStock`,
							`color`,
							`views`,
							`date`	
						) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
					
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("ssssssssssss", 
						$userData['name'], 
						$userData['ukrName'], 
						$userData['price'], 
						$userData['catId'], 
						$userData['catRu'], 
						$userData['catUkr'], 
						$userData['catEn'], 
						$userData['brand'],
						$userData['inStock'],
						$userData['color'],
						$userData['views'],
						$userData['date']
					);
					$stmt->execute() or die($this->mysqli->error.__LINE__);
					//GET LAST INSERTED RECORD's ID:
					$result_id = $stmt->insert_id;
					$stmt->close();
					
					if($result_id){
						//create new directory for the product:
						$path = "../assets/img/pri/$result_id";
						mkdir($path);
						//insert info about the product to availpri table:
						$query = "INSERT INTO availpri (`productId`) VALUES('$result_id')";
						$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					    $this->response(json_encode($result_id), 200);
					} else {
					    $this->response('DB error',204);
					}
				} else {
					$this->response(json_encode("Can't update"), 406);
				}
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		// ==== PRODUCT: UPDATE ====
		private function updateProduct(){
			if($this->get_request_method() != "PUT"){
				$this->response('',406);
			}

			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can't create anything;
						break;
					case 'admin':
						//admins are allowed:
						$proceed = true;
						break;
					case 'super':
						//can create everything:
						$proceed = true;
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {
					$query = "UPDATE products SET 
						name = ?, 
						approved = ?, 
						ukrName = ?, 
						price = ?, 
						catId = ?, 
						catRu = ?, 
						catUkr = ?, 
						catEn = ?, 
						brand = ?, 
						descrRu = ?, 
						descrUkr = ?, 
						color = ?, 
						views = ?, 
						inStock = ?, 
						images = ?, 
						videos = ?, 
						tech = ?, 
						techUkr = ?, 
						parts = ?,					
						partsUkr = ?					
						WHERE id = ?";
					
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("sssssssssssssssssssss", 
						$userData['name'], 
						$userData['approved'], 
						$userData['ukrName'], 
						$userData['price'], 
						$userData['catId'], 
						$userData['catRu'], 
						$userData['catUkr'], 
						$userData['catEn'], 
						$userData['brand'],
						$userData['descrRu'],
						$userData['descrUkr'],
						$userData['color'],
						$userData['views'],
						$userData['inStock'],
						$userData['images'],
						$userData['videos'],
						$userData['tech'],
						$userData['techUkr'],
						$userData['parts'],
						$userData['partsUkr'],
						$userData['id']
					);
					$stmt->execute() or die($this->mysqli->error.__LINE__);
					$stmt->close();
					$this->response(json_encode("update successful, cat ID:".$userData['id']), 200);
				} else {
					$this->response(json_encode("Can't update"), 406);
				}
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}


		// ==== GET USERS ====
		private function getUsers(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT DISTINCT 
					u.id, 
					u.login, 
					u.name, 
					u.status
					FROM users AS u";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$users = array();
				while($row = $r->fetch_assoc()){
					$row['id'] = (int)$row['id'];
					$users[] = $row;
				}
			    $this->response(json_encode($users), 200);
			} else {
			    $this->response('',204);
			}
		}

		// ==== UPDATE USER ====
		private function updateUser(){
			if($this->get_request_method() != "PUT"){
				$this->response('',406);
			}
			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can only update themselves:
						if( (int)$userData['id'] === (int)$user->id ) {
							$proceed = true;
						}
						break;
					case 'admin':
						//can update himself:
						if( (int)$userData['id'] === (int)$user->id ) {
							$proceed = true;
						}
						//and other users:
						if ( $userData['status'] === "user" ) {
							$proceed = true;
						}
						break;
					case 'super':
						//can update everything:
						$proceed = true;
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {
					if(!empty($userData['newPass'])){
						//ENCRYPT USER'S NEW PASSWORD AND UPDATE WHOLE RECORD:
						$encPass = password_hash($userData['newPass'], PASSWORD_DEFAULT);
						$query = "UPDATE users SET login = ?, name = ?, pass = ?, status = ? WHERE id = ?";
						$stmt = $this->mysqli->prepare($query);
						$stmt->bind_param("sssss", 
							$userData['login'], 
							$userData['name'], 
							$encPass, 
							$userData['status'],
							$userData['id']);
					} else {
						//UPDATE EVERYTHING BESIDES THE PASSWORD:
						$query = "UPDATE users SET login = ?, name = ?, status = ? WHERE id = ?";
						$stmt = $this->mysqli->prepare($query);
						$stmt->bind_param("ssss", 
							$userData['login'], 
							$userData['name'], 
							$userData['status'],
							$userData['id']);
					}
					$stmt->execute() or die($this->mysqli->error.__LINE__);
					$stmt->close();
					$this->response(json_encode("Successfully updated user, ID:".$userData['id']), 200);
				} else {
					$this->response(json_encode("Can't update"), 406);
				}
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		// ==== DELETE USER ====
		private function deleteUser(){
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can't do anything:
						break;
					case 'admin':
						//can delete only other users:
						if ( $userData['status'] === "user" ) {
							$proceed = true;
						}
						break;
					case 'super':
						//can't delete himself, but rest is allowed:
						if( (int)$userData['id'] !== (int)$user->id ) {
							$proceed = true;
						}
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {
					if(!empty($userData['id'])){
						//CHECK IF SUCH USER EXISTS:
						$query = "SELECT * FROM users WHERE id = ? LIMIT 1";
						$stmt = $this->mysqli->prepare($query);
						$stmt->bind_param("s", $userData['id']);
						$stmt->execute() or die($this->mysqli->error.__LINE__);
						$result = $stmt->get_result();
						if($result->num_rows > 0){
							$stmt->close();
							//DELETE THIS USER NOW:
							$query = "DELETE FROM users WHERE id = ?";
							$stmt = $this->mysqli->prepare($query);
							$stmt->bind_param("s", $userData['id']);
							$stmt->execute() or die($this->mysqli->error.__LINE__);
							$stmt->close();
							$this->response(json_encode("Successfully deleted user, ID:".$userData['id']), 200);
						} else {
							$this->response(json_encode("User with ID: ".$userData['id']." doesn't exist"), 404);
						}
					} else {
						//IF THERE'S NO $userData['id']:
						$this->response(json_encode("Incomplete deleteUser data"), 406);
					}
				} else {
					//IF $proceed IS FALSE:
					$this->response(json_encode("Can't delete. Permission denied"), 406);
				}
			} else {
				//IF $user->authorized IS FALSE:
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		// ==== CREATE USER ====
		private function createUser(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can't create anything;
						break;
					case 'admin':
						//create only users:
						if ( $userData['status'] === "user" ) {
							$proceed = true;
						}
						break;
					case 'super':
						//can create everything:
						$proceed = true;
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {
					// AT FIRST CHECK IF USER WITH GIVEN LOGIN ALREDY EXISTS:
					$query="SELECT * FROM users WHERE login = ?";
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("s", $userData['login']);
					$stmt->execute() or die($this->mysqli->error.__LINE__);
					$result = $stmt->get_result();
					if($result->num_rows > 0){
						$stmt->close();
						//RESPOND WITH APPROPRIATE ERROR MSG:
						$this->response(json_encode("Пользователь с таким логином уже существует. Выберите другой."), 406);
					}
					//CONTINUE AND CREATE NEW USER IF LOGIN WAS UNIQUE:
					//ENCRYPT NEW USER'S PASSWORD:
					$encPass = password_hash($userData['pass'], PASSWORD_DEFAULT);
					$query = "INSERT INTO users (`login`,`name`,`pass`,`status`) VALUES (?,?,?,?)";
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("ssss", 
						$userData['login'], 
						$userData['name'], 
						$encPass, 
						$userData['status']);
					$stmt->execute() or die($this->mysqli->error.__LINE__);
					//GET LAST INSERTED RECORD's ID:
					$result_id = $stmt->insert_id;
					$stmt->close();
					$this->response(json_encode($result_id), 200);
				} else {
					$this->response(json_encode("Can't update"), 406);
				}
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		// ==== GET TODOS ====
		private function getTodos(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT DISTINCT 
					t.id, 
					t.userId, 
					t.done, 
					t.task
					FROM todos AS t";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$todos = array();
				while($row = $r->fetch_assoc()){
					$row['id'] = (int)$row['id'];
					$row['userId'] = (int)$row['userId'];
					$row['done'] = (bool)$row['done'];
					$todos[] = $row;
				}
			    $this->response(json_encode($todos), 200);
			} else {
			    $this->response('',204);
			}
		}

		// ==== UPDATE TODO TASK ====
		private function updateTask(){
			if($this->get_request_method() != "PUT"){
				$this->response('',406);
			}
			$data = json_decode(file_get_contents("php://input"),true);
			//never check boolean value 'false' for being empty, cuz it will always be empty...
			if( !empty($data['id']) ) {
				$query = "UPDATE todos SET done = ? WHERE id = ?";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $data['done'], $data['id']);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$stmt->close();
				$this->response(json_encode("Successfully updated todo task, ID:".$data['id']), 200);
			} else {
				$this->response(json_encode("Incomplete data"), 406);
			}
		}

		// ==== DELETE TODO TASK ====
		private function deleteTask(){
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$data = json_decode(file_get_contents("php://input"),true);
			//never check boolean value 'false' for being empty, cuz it will always be empty...
			if( !empty($data['id']) ) {
				$query = "DELETE FROM todos WHERE id = ?";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("s", $data['id']);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$stmt->close();
				$this->response(json_encode("Successfully deleted todo task, ID:".$data['id']), 200);
			} else {
				$this->response(json_encode("Incomplete data"), 406);
			}
		}

		// ==== CREATE TODO TASK ====
		private function createTask(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {

				$query = "INSERT INTO todos (`userId`,`task`) VALUES (?,?)";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", 
					$id, 
					$userData['task']);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				//GET LAST INSERTED RECORD's ID:
				$result_id = $stmt->insert_id;
				$stmt->close();
				$this->response(json_encode($result_id), 200);
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		//==== GET CONFIGS =====
		private function getConfigs(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT DISTINCT 
				c.phone1, 
				c.phone2, 
				c.phone3, 
				c.addressRu1, 
				c.addressRu2, 
				c.addressUkr1, 
				c.addressUkr2, 
				c.deliveryRu, 
				c.deliveryUkr, 
				c.socialIcon1, 
				c.socialIcon2, 
				c.socialIcon3, 
				c.socialIcon4, 
				c.socialIcon5, 
				c.socialLink1, 
				c.socialLink2, 
				c.socialLink3, 
				c.socialLink4, 
				c.socialLink5 FROM configs AS c";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			//There should be only one row in 'configurables' table:
			if($r->num_rows === 1){
			    $result = $r->fetch_assoc();
			    $this->response(json_encode($result), 200);
			} else if ($r->num_rows > 1) {
				$this->response(json_encode("Unexpected extra rows in configurables table"), 500);
			} else {
			    $this->response('',204);
			}
		}


		// ==== UPDATE CONFIGS ====
		private function updateConfigs(){
			if($this->get_request_method() != "PUT"){
				$this->response('',406);
			}
			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can't do anything here:
						break;
					case 'admin':
						//admins can change data:
						$proceed = true;
						break;
					case 'super':
						//can update everything:
						$proceed = true;
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {

					//UPDATE EVERYTHING:
					$query = "UPDATE configs AS c SET 
						c.phone1 = ?, 
						c.phone2 = ?, 
						c.phone3 = ?, 
						c.addressRu1 = ?, 
						c.addressRu2 = ?, 
						c.addressUkr1 = ?, 
						c.addressUkr2 = ?, 
						c.deliveryRu = ?, 
						c.deliveryUkr = ?, 
						c.socialIcon1 = ?, 
						c.socialIcon2 = ?, 
						c.socialIcon3 = ?, 
						c.socialIcon4 = ?, 
						c.socialIcon5 = ?, 
						c.socialLink1 = ?, 
						c.socialLink2 = ?, 
						c.socialLink3 = ?, 
						c.socialLink4 = ?, 
						c.socialLink5 = ?
						WHERE id = 1";
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("sssssssssssssssssss", 
						$userData['phone1'], 
						$userData['phone2'], 
						$userData['phone3'],
						$userData['addressRu1'],
						$userData['addressRu2'],
						$userData['addressUkr1'],
						$userData['addressUkr2'],
						$userData['deliveryRu'],
						$userData['deliveryUkr'],
						$userData['socialIcon1'],
						$userData['socialIcon2'],
						$userData['socialIcon3'],
						$userData['socialIcon4'],
						$userData['socialIcon5'],
						$userData['socialLink1'],
						$userData['socialLink2'],
						$userData['socialLink3'],
						$userData['socialLink4'],
						$userData['socialLink5']
					);

					$stmt->execute() or die($this->mysqli->error.__LINE__);
					$stmt->close();
					$this->response(json_encode("Successfully updated configs"), 200);
				} else {
					$this->response(json_encode("Can't update"), 406);
				}
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		// ==== LOGIN ====
		private function login(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$userData = json_decode(file_get_contents("php://input"),true);
			$login = $userData['login'];
			$pass = $userData['pass'];
			// $this->response(json_encode($userData), 200);
			if(!empty($login) && !empty($pass)){
				//SELECT USER RECORD WITH GIVEN LOGIN:
				$query="SELECT * FROM users WHERE login = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("s", $login);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$creds = array();
						$creds["id"] = (int)$r["id"];
						$creds["login"] = $r["login"];
						$creds["name"] = $r["name"];
						$creds["pass"] = $pass;
						$creds["status"] = $r["status"];
					  	$this->response(json_encode($creds), 200);
					} else {
						$stmt->close();
					  	$this->response(json_encode("Неверный пароль."), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("Неверный логин или пароль."), 406);
				}
			} else {
				$this->response(json_encode("Не заполненное поле логина или пароля"), 406);
			}
		}

		// ====== CREATE ORDER ====
		private function placeOrder(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$data = json_decode(file_get_contents("php://input"),true);
			//DOUBLE CHECK uniq IDENTIFIER AT FIRST:
			$uniq = preg_replace('/[^0-9]*/i', '', $data['uniq']);
			
			if(!empty($data)){
				//CHECK IF CUSTOMER WITH GIVEN PHONE NUMBER ALREADY EXISTS:
				$id = null;
				$history = null;
				
				$query = "SELECT * FROM customers WHERE uniq REGEXP ?";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("s", $uniq);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();
				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					$id = $r['id'];
					$history = $r['orders']; //string of previous orders;
					//in case when customer used several names with the same phone number:
					$n = trim($data['name']);
					$name = trim($r['name']);
					if($n!==$name) {
						$match = strpos($name, $n);
						if(!$match) {
							$n = $name.", ".$n;
						} else {
							$n = $name;
						}
					}
				}
				$stmt->close();
				
				//CREATE NEW CUSTOMER IF NEEDED:
				if($id === null) {
					$query = "INSERT INTO customers (`name`,`phone`,`uniq`) VALUES(?,?,?)";
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("sss", $data['name'], $data['phone'], $data['uniq']);
					$stmt->execute() or die($this->mysqli->error.__LINE__);
					$id = $stmt->insert_id;
					$stmt->close();
				}
				

				//CREATE NEW ORDER:
				$query = "INSERT INTO orders 
					(	`customerId`,
						`productId`,
						`quantity`,
						`orderCost`,
						`date`	) VALUES (?,?,?,?,?)";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("sssss", 
					$id, 
					$data['productId'], 
					$data['quantity'], 
					$data['orderCost'],
					$data['date']);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$orderId = $stmt->insert_id;
				$stmt->close();

				//ADD NEW ORDER ID TO CUSTOMER'S PURCHASE HISTORY:
				if($history === null) {
					$query = "UPDATE customers SET orders = '$orderId' WHERE id = $id";
				} else {
					$history = $history.",".$orderId;
					$query = "UPDATE customers SET orders = '$history', name = '$n' WHERE id = $id";
				}
				$r2 = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				// ===== SEND EMAIL ABOUT THE ORDER =====
				mail($this->_siteEmail, 
					"BikeRide: новый заказ", 
					"Принят новый заказ, №:".$orderId.".
					\nТовар: ".$data['productName'].";
					\nКоличество: ".$data['quantity'].";
					\nСумма: ".$data['orderCost']."грн;
					\nПокупатель: ".$data['name'].".
					\nНомер телефона: ".$data['phone']);
				// ==== SEND BACK NEW ORDER ID FOR CUSTOMER'S REFERENCE ====
			    $this->response(json_encode($orderId), 200);
			} else {
				$this->response('',500);
			}
		}

		// ==== GET ORDERS ====
		private function getOrders(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT * FROM orders";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$orders = array();
				while($row = $r->fetch_assoc()){
					$row['id'] = (int)$row['id'];
					$row['customerId'] = (int)$row['customerId'];
					$row['productId'] = (int)$row['productId'];
					$row['quantity'] = (int)$row['quantity'];
					$row['orderCost'] = (float)$row['orderCost'];
					$row['deliveryCost'] = (float)$row['deliveryCost'];
					$row['date'] = (float)$row['date'];
					$orders[] = $row;
				}
			    $this->response(json_encode($orders), 200);
			} else {
			    $this->response('',204);
			}
		}

		//UPDATE ORDER:
		private function updateOrder(){
			if($this->get_request_method() != "PUT"){
				$this->response('',406);
			}

			$order = json_decode(file_get_contents("php://input"),true);

			if(!empty($order)){				
				$query = "UPDATE orders SET 
					deliveryCost = ?, 
					status = ?, 
					notes = ? 
					WHERE id = ?";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ssss", 
					$order['deliveryCost'], 
					$order['status'], 
					$order['notes'], 
					$order['id']);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$stmt->close();
				$this->response(json_encode("updated order with id: ".$order['id']), 200);
			}else {
				$this->response('',204);
			}
		}

		// ==== GET CUSTOMERS ====
		private function getCustomers(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT * FROM customers";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$customers = array();
				while($row = $r->fetch_assoc()){
					$row['id'] = (int)$row['id'];
					if( (bool)$row['orders'] ){
						//If it is NOT NULL split it to array:
						$row['orders'] = explode(",", $row['orders']);
						//trim each array element and convert to number:
						for( $i=0; $i<count($row['orders']); $i++) {
							$row['orders'][$i] = (int)trim($row['orders'][$i]);
						}
					} else {
						//If it's NULL then send back an empty array:
						$row['orders'] = array();
					}
					$customers[] = $row;
				}
			    $this->response(json_encode($customers), 200);
			} else {
			    $this->response('',204);
			}
		}

		//UPDATE CUSTOMER:
		private function updateCustomer(){
			if($this->get_request_method() != "PUT"){
				$this->response('',406);
			}

			$customer = json_decode(file_get_contents("php://input"),true);
			
			if(!empty($customer)){				
				//if there was a change in customer's phone number, 
				//change his uniq identifier as well:
				$uniq = preg_replace('/[^0-9]*/i', '', $customer['phone']);
				if($uniq!==$customer['uniq']) {
					//UPDATE UNIQ:
					$query = "UPDATE customers SET 
						name = ?, 
						phone = ?, 
						email = ?, 
						address = ?, 
						notes = ?, 
						uniq = ? 
						WHERE id = ?";
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("sssssss", 
						$customer['name'], 
						$customer['phone'], 
						$customer['email'], 
						$customer['address'], 
						$customer['notes'], 
						$uniq, 
						$customer['id']);
				} else {
					//DON'T UPDATE UNIQ:
					$query = "UPDATE customers SET 
						name = ?, 
						phone = ?, 
						email = ?, 
						address = ?, 
						notes = ? 
						WHERE id = ?";
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("ssssss", 
						$customer['name'], 
						$customer['phone'], 
						$customer['email'], 
						$customer['address'], 
						$customer['notes'],
						$customer['id']);
				}
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$stmt->close();
				$this->response(json_encode("updated customer with id: ".$customer['id']), 200);
			} else {
				$this->response('',204);
			}
		}

		// ====== CREATE FEED ====
		private function createFeed(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$data = json_decode(file_get_contents("php://input"),true);
			
			if(!empty($data)){
				
				//CREATE NEW FEED:
				$query = "INSERT INTO feeds 
					(	`name`,
						`city`,
						`text` ) VALUES (?,?,?)";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("sss", 
					$data['name'],
					$data['city'], 
					$data['text']);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$stmt->close();

			    $this->response(json_encode("new feed has been added!"), 200);
			} else {
				$this->response('',500);
			}
		}

		// ==== GET FEEDS ====
		private function getFeeds(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT * FROM feeds";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$feeds = array();
				while($row = $r->fetch_assoc()){
					$row['id'] = (int)$row['id'];
					$feeds[] = $row;
				}
			    $this->response(json_encode($feeds), 200);
			} else {
			    $this->response('',204);
			}
		}

		//UPDATE FEED:
		private function updateFeed(){
			if($this->get_request_method() != "PUT"){
				$this->response('',406);
			}

			$feed = json_decode(file_get_contents("php://input"),true);
			
			if(!empty($feed)){				
				$query = "UPDATE feeds SET 
					name = ?, 
					city = ?, 
					approved = ?, 
					text = ? 
					WHERE id = ?";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("sssss", 
					$feed['name'], 
					$feed['city'], 
					$feed['approved'], 
					$feed['text'],
					$feed['id']);

				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$stmt->close();
				$this->response(json_encode("updated feed with id: ".$feed['id']), 200);
			} else {
				$this->response('',204);
			}
		}

		// ==== GET CATEGORIES ====
		private function getCats(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT * FROM cats";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$cats = array();
				while($row = $r->fetch_assoc()){
					$row['id'] = (int)$row['id'];
					$row['views'] = (int)$row['views'];
					$cats[] = $row;
				}
			    $this->response(json_encode($cats), 200);
			} else {
			    $this->response('',204);
			}
		}

		// ==== UPDATE CATEGORY ====
		private function updateCat(){
			if($this->get_request_method() != "PUT"){
				$this->response('',406);
			}
			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can't do anything here:
						break;
					case 'admin':
						//admins can change data:
						$proceed = true;
						break;
					case 'super':
						//can update everything:
						$proceed = true;
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {

					//UPDATE EVERYTHING:
					$query = "UPDATE cats AS c SET 
						c.views = ?, 
						c.src = ?, 
						c.ruName = ?, 
						c.ukrName = ?, 
						c.enName = ?
						WHERE id = ?";
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("ssssss", 
						$userData['views'], 
						$userData['src'], 
						$userData['ruName'],
						$userData['ukrName'],
						$userData['enName'],
						$userData['id']);

					$stmt->execute() or die($this->mysqli->error.__LINE__);
					$stmt->close();
					$this->response(json_encode("Successfully updated category with ID: ".$userData['id']), 200);
				} else {
					$this->response(json_encode("Can't update"), 406);
				}
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		// ==== CREATE CATEGORY ====
		private function createCat(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can't create anything;
						break;
					case 'admin':
						//admins are allowed:
						$proceed = true;
						break;
					case 'super':
						//can create everything:
						$proceed = true;
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {
					$query = "INSERT INTO cats (`views`,`ruName`,`ukrName`,`enName`) VALUES (?,?,?,?)";
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("ssss", 
						$userData['views'], 
						$userData['ruName'], 
						$userData['ukrName'], 
						$userData['enName']);
					$stmt->execute() or die($this->mysqli->error.__LINE__);
					//GET LAST INSERTED RECORD's ID:
					$result_id = $stmt->insert_id;
					$stmt->close();
					$this->response(json_encode($result_id), 200);
				} else {
					$this->response(json_encode("Can't update"), 406);
				}
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		// ==== GET PROMOTIONS ====
		private function getProms(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT * FROM promotions";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$promotions = array();
				while($row = $r->fetch_assoc()){
					$row['id'] = (int)$row['id'];
					$promotions[] = $row;
				}
			    $this->response(json_encode($promotions), 200);
			} else {
			    $this->response('',204);
			}
		}

		// ==== UPDATE PROMOTION ====
		private function updateProm(){
			if($this->get_request_method() != "PUT"){
				$this->response('',406);
			}
			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can't do anything here:
						break;
					case 'admin':
						//admins can change data:
						$proceed = true;
						break;
					case 'super':
						//can update everything:
						$proceed = true;
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {

					//UPDATE EVERYTHING:
					$query = "UPDATE promotions AS p SET 
						p.src = ?, 
						p.onsite = ?,
						p.title = ?, 
						p.titleUkr = ?, 
						p.text = ?, 
						p.textUkr = ? 
						WHERE id = ?";
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("sssssss", 
						$userData['src'], 
						$userData['onsite'], 
						$userData['title'], 
						$userData['titleUkr'],
						$userData['text'],
						$userData['textUkr'],
						$userData['id']);

					$stmt->execute() or die($this->mysqli->error.__LINE__);
					$stmt->close();
					$this->response(json_encode("Successfully updated promotion with ID: ".$userData['id']), 200);
				} else {
					$this->response(json_encode("Can't update"), 406);
				}
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		// ==== CREATE PROMOTION ====
		private function createProm(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$userData = json_decode(file_get_contents("php://input"),true);
			//Container for info about current user:
			$user = new StdClass();
			//Authorize first:
			$current = $userData['current'];

			$id = $current['id'];
			$login = $current['login'];
			$pass = $current['pass'];
			

			if( !empty($id) && !empty($login) && !empty($pass) ){
				//SELECT USER RECORD WITH GIVEN NAME:
				$query="SELECT * FROM users WHERE login = ? AND id = ? LIMIT 1";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("ss", $login, $id);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$result = $stmt->get_result();

				if($result->num_rows > 0){
					$r = $result->fetch_assoc();
					
					if(password_verify($pass, $r['pass'])){
						$stmt->close();
						$user->authorized = true;
						$user->status = $r['status'];
						$user->id = $r['id'];
					} else {
						$stmt->close();
					  	$this->response(json_encode("Authorization failed"), 406);
					}
				} else {
					$stmt->close();
					$this->response(json_encode("User doesn't exist"), 406);
				}
			} else {
				$this->response(json_encode("Incomplete user data"), 406);
			}

			//FURTHER VALIDATION:
			if($user->authorized) {
				$proceed = false;
				switch ($user->status) {
					case 'user':
						//users can't create anything;
						break;
					case 'admin':
						//admins are allowed:
						$proceed = true;
						break;
					case 'super':
						//can create everything:
						$proceed = true;
						break;
					default:
						break;
				}
				//WHEN STATUS IS CONFIRMED:
				if($proceed) {
					$query = "INSERT INTO promotions (`title`,`titleUkr`,`text`,`textUkr`) VALUES (?,?,?,?)";
					$stmt = $this->mysqli->prepare($query);
					$stmt->bind_param("ssss", 
						$userData['title'], 
						$userData['titleUkr'], 
						$userData['text'], 
						$userData['textUkr']);
					$stmt->execute() or die($this->mysqli->error.__LINE__);
					//GET LAST INSERTED RECORD's ID:
					$result_id = $stmt->insert_id;
					$stmt->close();
					$this->response(json_encode($result_id), 200);
				} else {
					$this->response(json_encode("Can't update"), 406);
				}
			} else {
				$this->response(json_encode("User is not accepted"), 406);
			}
		}

		// ==== TRACK VIEWS ====
		private function trackViews(){	
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$data = json_decode(file_get_contents("php://input"),true);
			//UPDATE PRODUCT:
			if(!empty($data)){
				$query = "UPDATE products AS p SET 
					p.views = p.views + 1
					WHERE id = ?";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("s", $data['prodId']);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$stmt->close();

				//UPDATE CATEGORY:
				$query = "UPDATE cats AS c SET 
					c.views = c.views + 1
					WHERE id = ?";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("s", $data['catId']);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$stmt->close();

				//INSERT NEW ROW INTO TRACKVIEWS:
				$query = "INSERT INTO trackviews (`productId`) VALUES (?)";
				$stmt = $this->mysqli->prepare($query);
				$stmt->bind_param("s", 
					$data['prodId']);
				$stmt->execute() or die($this->mysqli->error.__LINE__);
				$stmt->close();

				//DELETE OLD RECORDS FROM TRACKVIEWS:
				$patern = date('Y-m-d');
				$query = "DELETE FROM trackviews WHERE date NOT REGEXP '^$patern'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				
				$this->response(json_encode("updated views"), 200);
			} else {
				$this->response('',406);
			}
		}

		// ==== GET TRACKVIEWS ====
		private function getViews(){	
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$patern = date('Y-m-d');
			$query="SELECT * FROM trackviews WHERE date REGEXP '^$patern'";
			//Send request and get results or 
			//report error msg + current line's number in script;
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0){
				$views = array();
				while($row = $r->fetch_assoc()){
					$row['id'] = (int)$row['id'];
					$row['productId'] = (int)$row['productId'];
					$views[] = $row;
				}
			    $this->response(json_encode($views), 200);
			} else {
				$views = array();
			    $this->response(json_encode($views), 200);
			}
		}
		
		// ================== END ===================
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>