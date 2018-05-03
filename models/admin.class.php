<?php
class admin{
	private $db;
	public function __construct ($dbConnection) {
		$this->db = $dbConnection;
	}
	function getAccount($id){
		$sql = "SELECT *
				FROM account_info
				WHERE account_id = ?";
				$statement = $this->db->prepare($sql);
				$data = array( $id );
				$statement->execute($data);
				// This model part is important if you want it to come back to you in the form of an object.
				$model = $statement->fetchObject();
				return $model;
	}
	function getAllAccounts(){
		$sql = "SELECT account_id, first_name, last_name, email, user_name, password
				FROM account_info
				WHERE admin = 0";
				$statement = $this->db->prepare($sql);
				$statement->execute();
				return $statement;
	}
	function getAllEvents(){
		$sql = "SELECT *
				FROM event_info";
				$statement = $this->db->prepare($sql);
				$statement->execute();
				return $statement;
	}
	function getCheckboxVariables(){
		$allAccounts = $this->getAllAccounts();
		$checkboxVariables = "";
		while ($account = $allAccounts->fetchObject() ){
			// if($account->admin == "1" ){
			// }else{
			$checkboxVariables .= "<input type='checkbox' name='allIncluded[]' value='$account->account_id' >$account->first_name $account->last_name:$account->user_name<br>";
			// }
		}
		return $checkboxVariables;
	}
	function getLiveEvents(){
		date_default_timezone_set("America/Phoenix");
		$currentDateTime = date("Y-m-d H:i:s");
		$allEvents = $this->getAllEvents();
		$liveEvents = "";
		while($event = $allEvents->fetchObject() ){
			if ($event->event_datetime > $currentDateTime){
				$href = "index.php?page=event&amp;id=$event->event_id";
				$liveEvents .= "<p>$event->event_name <a href='$href'>read more</a></p>";
			}
		}
		return $liveEvents;
	}
	function getFormerEvents(){
		date_default_timezone_set("America/Phoenix");
		$currentDateTime = date("Y-m-d H:i:s");
		$allEvents = $this->getAllEvents();
		$formerEvents = "";
		while($event = $allEvents->fetchObject() ){
			if ($event->event_datetime < $currentDateTime){
				$href = "index.php?page=event&amp;id=$event->event_id";
				$formerEvents .= "<p>$event->event_name <a href='$href'>read more</a></p>";
			}
		}
		return $formerEvents;
	}
	function checkEvent($eventName, $date, $time, $allIncluded){
		$errorMessage = "";
		if (empty($eventName) || empty($date) || empty($time) || empty($allIncluded)){
		$errorMessage = "<p>You are missing either a name, date, time or included group </p>";
		}elseif(sizeof($allIncluded) == 1){
			$errorMessage = "<p>You only have one person in the included list which makes a \"Secret Santa\" impossible<p>";
		}else{
			$this->saveEvent($eventName, $date, $time, $allIncluded);
		}
		return $errorMessage;
	}
	function getEvent($eventName){
		$sql = "SELECT (event_id)
				FROM event_info
				WHERE event_name = ?";
				$statement = $this->db->prepare($sql);
				$data = array( $eventName );
				$statement->execute($data);
				// This model part is important if you want it to come back to you in the form of an object.
				$model = $statement->fetchObject();
				return $model;
	}
	function assignBuddies($allIncluded){
		$buddyList = array();
	 	$givers = $allIncluded;
	 	$receivers = $allIncluded;
	 	foreach ($givers as $user){
	 		$notAssigned = true;
	 		while($notAssigned){
	 			$choice = rand(0, sizeof($receivers)-1);
	 			if($user !== $receivers[$choice]){
	 				$buddyList["$user"] = $receivers[$choice];
	 				unset($receivers[$choice]);
	 				$receivers = array_values($receivers);
	 				$notAssigned = false;
	 			}else{
	 				if(sizeof($receivers) == 1){
	 					$buddyList["$user"] = $buddyList[$givers[0] ];
	 					$buddyList[$givers[0] ] = $user;
	 					$notAssigned = false;
	 				}
	 			}
	 		}
	 	}
	 	return $buddyList;
	}
	function saveEventInclusion($buddyList, $event_id){
		foreach ($buddyList as $account_id => $buddy_id){
			$sql = "INSERT INTO event_inclusion (event_id, account_id, buddy_id)
					VALUES (?, ?, ?)";
					$statement = $this->db->prepare( $sql );
					$data = array($event_id, $account_id, $buddy_id);
					$statement->execute($data);
				}
	}
	function saveEvent($eventName, $date, $time, $allIncluded){
		$buddyList = $this->assignBuddies($allIncluded);
		$dateTime = "$date $time";
		$sql = "INSERT INTO event_info (event_name, event_datetime)
				VALUES (?, ?)";
				$statement = $this->db->prepare( $sql );
				$data = array($eventName, $dateTime);
				$statement->execute($data);
		$buddyList = $this->assignBuddies($allIncluded);
		$event_idObject = $this->getEvent($eventName);
		$event_id = $event_idObject->event_id;
		$this->saveEventInclusion($buddyList, $event_id);
	}





	function checkStageEvent($eventName, $date, $time, $description, $host_id){
			if (empty($_POST['event-name']) || empty($_POST['date']) || empty($_POST['time'])){
		$errorMessage = "<p>You are missing the following: ";
		$fieldArray = array("event-name", "date", "time");
		$firstTime = true;
		foreach($fieldArray as $field){
			if(empty($_POST['$field'])){
				if ($firstTime){
					$errorMessage .= $field;
					$firstTime = false;
				}else{
					$errorMessage .= ", $field";
				}
			}
		}
	}else{
		if (empty($description)){
			$description = "";
		}
		$errorMessage = "Event Saved";
		$this->saveStageEvent($eventName, $date, $time, $description, $host_id);
	}
	return $errorMessage;
	}
	function saveStageEvent($eventName, $date, $time, $description, $host_id){
		$dateTime = "$date $time";
		$status = "staging";

		$sql = "INSERT INTO event_info (event_name, event_datetime, description, host_id, status)
				VALUES (?, ?, ?, ?, ?)";
				$statement = $this->db->prepare( $sql );
				$data = array($eventName, $dateTime, $description, $host_id, $status);
				$statement->execute($data);
	}
	function getAllHostStagingEvents($host_id){
		$sql = "SELECT *
				FROM event_info
				WHERE host_id = ?
				AND status = ?";
		$statement = $this->db->prepare($sql);
		$data = array( $host_id, "staging" );
		$statement->execute($data);	
		$model = $statement->fetchObject();
								//unfinished	
	}









	function redirect( $url, $statusCode){
		header('Location: ' . $url, true, $statusCode);
		die();
	}
	function getEventPageInfo($event_id){
		$sql = "SELECT *
				FROM event_info
				WHERE event_id = ?";
				$statement = $this->db->prepare($sql);
				$data = array( $event_id );
				$statement->execute($data);
				// This model part is important if you want it to come back to you in the form of an object.
				$model = $statement->fetchObject();
				return $model;
	}
	function getEventPageInclusion($event_id){
		$sql = "SELECT *
				FROM event_inclusion
				WHERE event_id = ?";
				$statement = $this->db->prepare($sql);
				$data = array( $event_id );
				$statement->execute($data);
				return $statement;
	}
	function deleteEvent($event_id){
		$this->deleteEventInclusion($event_id);
		$sql = "DELETE FROM event_info
				WHERE event_id = ?";
				$data = array($event_id);
				$statement = $this->db->prepare($sql);
				$statement->execute($data);
	}
	function deleteEventInclusion($event_id){
		$sql = "DELETE FROM event_inclusion
				WHERE event_id = ?";
				$data = array($event_id);
				$statement = $this->db->prepare($sql);
				$statement->execute($data);
	}





}
// This website went a long way to making the logic for assigning buddies possible "https://github.com/thybag/PHP-Secret-Santa/blob/master/Santa.class.php".