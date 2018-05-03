<?php
class Account{
	private $db;
	public function __construct ($dbConnection) {
		$this->db = $dbConnection;
	}
	function getAllAccounts(){
		$sql = "SELECT account_id, first_name, last_name, email, user_name, password
				FROM account_info";
				$statement = $this->db->prepare($sql);
				$statement->execute();
				return $statement;
			}
	function checkAccount(){

		$fn = empty( $_POST['first-name'] );
		$ln = empty( $_POST['last-name'] );
		$ee = empty( $_POST['email'] );
		$un = empty( $_POST['username'] );
		$p1 = empty( $_POST['password'] );
		$p2 = empty( $_POST['password2'] );

		$firstName = $_POST['first-name'];
		$lastName = $_POST['last-name'];
		$email = $_POST['email'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];

		$errorMessage = "";
		$accounts = $this->getAllAccounts();

		if ($fn || $ln ||$ee || $un || $p1 || $p2 ){
			$fieldArrayVariable = array($fn, $ln, $ee, $un, $p1, $p2);
			$fieldArrayNames = array("First-Name", "Last-Name", "Email", "Username", "Password", "Confirm Password");
			$errorMessage .= "<p>*You must fill all of the fields. You are missing: ";
			$firstError = true;
			for ( $i = 0; $i < 6; $i++ ){
			
				if ($fieldArrayVariable[$i]){
					if($firstError){
						$errorMessage .= "$fieldArrayNames[$i]";
						$firstError = false;
					}
					else {
						$errorMessage .= ", $fieldArrayNames[$i]";
					}
				}	
			}
			$errorMessage .= ".</p>";

		return $errorMessage;
		}elseif ($password !== $password2){
			$errorMessage .= "<p>*The password and the confirmation password don't match up. Check for caps and anthing else that would mess this up.</p>";
		}else {
			$errorMessage = "<p>Account saved!</p>";
		}
		while($account = $accounts->fetchObject() ){
			if( $username == $account->user_name){
				$errorMessage = "<p>*I'm sorry, there is already an account with that username. Either log on with that account or create a new one.</p>";
				return $errorMessage;
			 }
		}
	return $errorMessage;	
	} 
	function saveAccount($fName, $lName, $email, $username, $password){
		//I changed this part
		$pin = intval( "0" . rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9)  );
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);
		$fullName = "$fName $lName$pin";


		$accountSQL = "INSERT INTO account_info (first_name, last_name, email, user_name, password, full_name ) 
						VALUES (?, ?, ?, ?, ?, ? ) ";
		$accountStatement = $this->db->prepare( $accountSQL );
		$formData = array( $fName, $lName, $email, $username, $hashedPassword, $fullName );  //I changed this too.
		$accountStatement->execute($formData);
	}
	function saveBio($account_id, $bio, $hobbies_Likes, $other){
		$sql = "UPDATE account_info
				SET bio = ?,
				hobbies_likes = ?,
				other = ?
				WHERE account_id = ?";
				$statement = $this->db->prepare($sql);
				$data = array($bio, $hobbies_Likes, $other, $account_id);
				$statement->execute($data);

	}
	function getName($account_id){
		$sql = "SELECT *
				FROM account_info
				WHERE account_id = ?";
		$statement = $this->db->prepare($sql);
		$data = array($account_id);
		$statement->execute($data);
		$model = $statement->fetchObject();
		$name = "$model->first_name $model->last_name";
		return $name;
	}
	function getWishListItems($account_id, $viewer_id){
		$sql = "SELECT *
				FROM wish_list
				WHERE account_id = ?";
				$statement = $this->db->prepare($sql);
				$data = array($account_id);
				$statement->execute($data);
				$wishListItems = "";
				
		while($wish = $statement->fetchObject()){
			$dibNames = "";
			if ($viewer_id !== $account_id){
				$theWishDibs = $this->getDibs($wish->wish_id);
				$q = true;
				if($theWishDibs){
					while ($wishDibs = $theWishDibs->fetchObject()){
						if ($q == true){
							$dibNames .= $this->getName($wishDibs->giver_id);
							$q = false;
						}else{
							$dibNames .= ", " . $this->getName($wishDibs->giver_id);
						}
					}
				}
			}
			$wishListItems .= "<input type='checkbox' name='allwishes[]' value='$wish->wish_id' />$wish->value $dibNames<br>";
		}
		return $wishListItems;
	}

	function saveWish($account_id, $wish){
		$sql = "INSERT INTO wish_list (account_id, value)
				VALUES (?, ?)";
				$statement = $this->db->prepare($sql);
				$data = array($account_id, $wish);
				$statement->execute($data);
	}
	function deleteWishes($checkedWishes){
		foreach($checkedWishes as $wish_id){
			$this->deleteWish($wish_id);
		}
	}
	function deleteWish($wish_id){

		$sql = "DELETE FROM wish_list WHERE wish_id = ?";
		$data = array($wish_id);
		$statement = $this->db->prepare($sql);
		$statement->execute($data);

		$sql = "DELETE FROM dibs WHERE wish_id = ?";
		$data = array($wish_id);
		$statement = $this->db->prepare($sql);
		$statement->execute($data);
	}
	function getDibs ($wish_id){
		$sql = "SELECT *
				FROM dibs
				WHERE wish_id = ?";
		$statement = $this->db->prepare($sql);
		$data = array($wish_id);
		$statement->execute($data);
		return $statement;

	}
	function getAllDibs(){
		$sql = "SELECT *
				FROM dibs";
				$statement = $this->db->prepare($sql);
				$statement->execute();
				return $statement;
			}
	function addDibs ($checkedWishes, $giver_id){
		foreach($checkedWishes as $wish_id){
			$r = $this->checkDibs($wish_id, $giver_id);
			if ($r == "exists"){
			}else{
				$sql = "INSERT INTO dibs (wish_id, giver_id)
						VALUES (?, ?)";
						$statement = $this->db->prepare($sql);
						$data = array($wish_id, $giver_id);
						$statement->execute($data);
					}
				}
	}
	function removeDibs($checkedWishes, $giver_id){
		foreach($checkedWishes as $wish_id){
			$r = $this->checkDibs($wish_id, $giver_id);
			if ($r == "exists"){
				$sql = "DELETE FROM dibs WHERE wish_id = ? AND giver_id = ?
						";
						$statement = $this->db->prepare($sql);
						$data = array($wish_id, $giver_id);
						$statement->execute($data);
			}
		}
	}

	function checkDibs($wish_id, $giver_id){
		$allDibs = $this->getAllDibs();
		$r = "";
		while ($dib = $allDibs->fetchobject()){
			if($wish_id == $dib->wish_id && $giver_id == $dib->giver_id){
				$r = "exists";
				return $r;
			}
		}
		return $r;
	}

	function getSuggestionItems ($profileID){
		$sql = "SELECT *
				FROM suggestion
				WHERE for_aid = ?";
		$statement = $this->db->prepare($sql);
		$data = array($profileID);
		$statement->execute($data);
		$suggestionItems = "";
		while ($suggestion = $statement->fetchObject()){
			$sDibNames = "";
			$theSuggestionDibs = $this->getSDibs($suggestion->suggestion_id);
			$q = true;
			$from_aidName = $this->getName($suggestion->from_aid);
			if ($theSuggestionDibs){
				while ($suggestionDibs = $theSuggestionDibs->fetchObject()){
					if ($q){
						// the error here is that I still don't have a dibs table to check if this person does indeed want dibs. I am checking the wrong information.
						$sDibNames .= $this->getName($suggestionDibs->giver_id);
						$q = false;
					}else{
						$sDibNames .= ", " . $this->getName($suggestionDibs->giver_id);
					}
				}
			}
			$from = "from $from_aidName";
			if (empty($sDibNames)){
				$dibs = "";
			}else{
				$dibs = "Dibs: $sDibNames";
			}
			$suggestionItems .= "<input type='checkbox' name='allsuggestions[]' value='$suggestion->suggestion_id' />$suggestion->value $from $dibs<br>";
		}
		return $suggestionItems;
	}

	function getSDibs ($suggestion_id){
		$sql = "SELECT *
				FROM s_dibs
				WHERE suggestion_id = ?";
		$statement = $this->db->prepare($sql);
		$data = array($suggestion_id);
		$statement->execute($data);
		return $statement;
	}
	
	function saveSuggestion ($suggestion, $profileID, $user_id){
		$sql = "INSERT INTO suggestion (value, for_aid, from_aid)
				VALUES (?, ?, ?)";
				$statement = $this->db->prepare($sql);
				$data = array($suggestion, $profileID, $user_id);
				$statement->execute($data);
	}
	function addSDibs($checkedSuggestions, $giver_id){
		foreach($checkedSuggestions as $suggestion_id){
			$r = $this->checkSDibs($suggestion_id, $giver_id);
			if ($r == "exists"){
			}else{
				$sql = "INSERT INTO s_dibs (suggestion_id, giver_id)
						VALUES (?, ?)";
				$statement = $this->db->prepare($sql);
				$data = array($suggestion_id, $giver_id);
				$statement->execute($data);
			}
		}
	}
	function checkSDibs($suggestion_id, $giver_id){
		$allSDibs = $this->getAllSDibs();
		$r = "";
		while ($sdib = $allSDibs->fetchobject()){
			if($suggestion_id == $sdib->suggestion_id && $giver_id == $sdib->giver_id){
				$r = "exists";
				return $r;
			}
		}
		return $r;
	}


	function getAllsDibs(){
		$sql = "SELECT *
				FROM s_dibs";
				$statement = $this->db->prepare($sql);
				$statement->execute();
				return $statement;
			}
	function removeSDibs($checkedSuggestions, $giver_id){
		foreach($checkedSuggestions as $suggestion_id){
			$r = $this->checkSDibs($suggestion_id, $giver_id);
			if ($r == "exists"){
				$sql = "DELETE FROM s_dibs WHERE suggestion_id = ? AND giver_id = ?
						";
						$statement = $this->db->prepare($sql);
						$data = array($suggestion_id, $giver_id);
						$statement->execute($data);
			}
		}
	}
	function getSuggestion($suggestion_id){
		$sql = "SELECT *
				FROM suggestion
				WHERE suggestion_id = ?";
		$statement = $this->db->prepare($sql);
		$data = array($suggestion_id);
		$statement->execute($data);
		$model = $statement->fetchObject();
		return $model;
	}

		function deleteSuggestions($checkedSuggestions, $user_id){
		foreach($checkedSuggestions as $suggestion_id){
			$suggestion = $this->getSuggestion($suggestion_id);
			if ($suggestion->from_aid == $user_id){
				$this->deleteSuggestion($suggestion_id);
			}
		}
	}
	function deleteSuggestion($suggestion_id){

		$sql = "DELETE FROM suggestion WHERE suggestion_id = ?";
		$data = array($suggestion_id);
		$statement = $this->db->prepare($sql);
		$statement->execute($data);

		$sql = "DELETE FROM s_dibs WHERE suggestion_id = ?";
		$data = array($suggestion_id);
		$statement = $this->db->prepare($sql);
		$statement->execute($data);
	}





	function keepFieldData(){
	$fieldData = new StdClass ();
	$fieldData->firstName = $_POST['first-name'];
	$fieldData->lastName = $_POST['last-name'];
	$fieldData->email = $_POST['email'];
	$fieldData->username = $_POST['username'];
	return $fieldData;
	}
	
	function checkLogin($username, $password){
		$errorMessage = "";
		if (empty($username) || empty($password) ){
			$errorMessage = "<p>*You must  in a username and password<p>";
		}
		$accounts = $this->getAllAccounts();

		while( $account = $accounts->fetchObject() ){
			if ( $account->user_name == $username){
				// if ($account->password == $password) {
				if (password_verify($password, $account->password)){
					$errorMessage = "";
					return $errorMessage;
				}else{
					$errorMessage = "<p> *Either you username or password were incorrect, make sure your spelling is correct and that you do not have your caps on</p>";
				}
			}else{
				$errorMessage = "<p> *Either you username or password were incorrect, make sure your spelling is correct and that you do not have your caps on</p>";
			}
		} 
		return $errorMessage;
	}
	function getAccount( $username ){
		$sql = "SELECT *
				FROM account_info
				WHERE user_name = ?";
				$statement = $this->db->prepare($sql);
				$data = array( $username );
				$statement->execute($data);
				// This model part is important if you want it to come back to you in the form of an object.
				$model = $statement->fetchObject();
				return $model;
	}
	function getAccount2($id){
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

	function redirect( $url, $statusCode){
		header('Location: ' . $url, true, $statusCode);
		die();
	}
	function getEvent($event_id){
		$sql = "SELECT *
				FROM event_info
				WHERE event_id = ?";
				$statement = $this->db->prepare($sql);
				$data = array($event_id);
				$statement->execute($data);
				$model = $statement->fetchobject();
				return $model;
	}
	function getEventOptions($account_id, $choice){
		$sql = "SELECT event_inclusion.account_id, event_inclusion.event_id, event_info.event_name, event_info.event_datetime
				FROM event_inclusion, event_info
				WHERE event_inclusion.event_id = event_info.event_id
				HAVING event_inclusion.account_id = ?";
		$statement = $this->db->prepare($sql);
		$data = array($account_id);
		$statement->execute($data);
		if ($choice == "options"){
			$eventOptions = "";
			while($option = $statement->fetchObject()){
				$eventOptions .= "<option value='$option->event_id'> $option->event_name</option>";
			}
			return $eventOptions;
		}
		if ($choice == "latest"){
			$edt = 0;
			$eventID = 0;
			while ($event = $statement->fetchobject()){
				if ($event->event_datetime > $edt){
					$edt = $event->event_datetime;
					$eventID = $event->event_id;
				}
			}
			return $eventID;
		}
	}
	function getBuddyID($event_id, $account_id){
		$sql = "SELECT *
				FROM event_inclusion
				WHERE event_id = ?
				AND account_id = ?";
		$statement = $this->db->prepare($sql);
		$data = array($event_id, $account_id);
		$statement->execute($data);
		$model = $statement->fetchobject();
		return $model->buddy_id;
	}
	function getAllViewableProfiles($account_id, $event_id, $buddy_id){
		$sql = "SELECT *
				FROM event_inclusion
				WHERE event_id = ?";
		$statement = $this->db->prepare($sql);
		$data = array($event_id);
		$statement->execute($data);
		$allViewableProfiles = "";
		$thisIsTag = "";

		while ($p = $statement->fetchObject()){
			$Account = $this->getAccount2($p->account_id);
			$thisIsTag = "";
			$profile = $Account->account_id;
			if ($account_id == $Account->account_id){
				$thisIsTag = "-This is You";
				// $profile = "your-account";
			}else if ($buddy_id == $Account->account_id){
				$thisIsTag = "-This is your buddy";
			}
			$allViewableProfiles .= "<li><a href='index.php?page=wish-lists&amp;profile=$profile'>$Account->first_name $Account->last_name$thisIsTag</a></li>";
		}
		
		return $allViewableProfiles;
	}







	function getAllStagingEvents($user_id){
		$sql = "SELECT *
				FROM event_info
				WHERE status = ?";
		$statement = $this->db->prepare($sql);
		$data = array("staging");
		$statement->execute($data);

		$output = "";
		while ($event = $statement->fetchObject()){
			$eventName = $event->event_name;
			$event_id = $event->event_id;
			$host_id = $event->host_id;
			$host_account = $this->getAccount2($host_id);
			$host_name = $host_account->full_name;
			$href = "index.php?page=events&amp;id=$event->event_id";
			$r = $this->checkForApplication($user_id, $event_id);
			if ($host_id == $user_id){
				$output .= "<p><a href='$href'>$eventName</a> hosted by $host_name</p>";
				$output .= "<form method='post' action='index.php?page=events'>
							<input type='hidden' name='event-id' value='$event_id' />
							<input type='submit' name='go-to-hosting' value='Go To Hosting' />
							</form>";
			}elseif ($r == "exists"){
				$output .= "<p><a href='$href'>$eventName</a> hosted by $host_name</p>";
				$output .= "<form method='post' action='index.php?page=events'>
							<input type='hidden' name='event-id' value='$event_id' />
							<input type='submit' name='withdraw-application' value='Withdraw Application' />
							</form>";
			}
			//needs an option for if you already have applied and want to withdraw
			else{
				$output .= "<p><a href='$href'>$eventName</a> hosted by $host_name</p>";
				$output .= "<form method='post' action='index.php?page=events'>
							<input type='hidden' name='event-id' value='$event_id' />
							<input type='submit' name='submit-application' value='Apply Now!' />
							</form>";
			}
		}
		return $output;
	}
	function getButton($user_id, $event_id, $host_id){
		$r = $this->checkForApplication($user_id, $event_id);
	if ($user_id == $host_id){
		$button = "<form method='post' action='index.php?page=events&amp;id=$event_id'>
					<input type='submit' name='go-to-hosting' value='Go To Hosting' />
					</form>";
	}else if ($r == "exists"){
		$button = "<form method='post' action='index.php?page=events&amp;id=$event_id'>
					<input type='submit' name='withdraw-application' value='Withdraw Application' />
					</form>";
	}else{
		$button = "<form method='post' action='index.php?page=events&amp;id=$event_id'>
					<input type='submit' name='submit-application' value='Apply Now!' />
					</form>";
				}
		return $button;
	}
		function getEventInfo($event_id){
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
	function checkForApplication($user_id, $event_id){
		$allApplications = $this->getAllApplications();
		$r = "";
		while($application = $allApplications->fetchObject()){
			if ($user_id == $application->applicant_id && $event_id == $application->event_id){
				$r = "exists";
				return $r;
			}
		}
		return $r;
	}
	function getAllApplications(){
		$sql = "SELECT *
				FROM event_applicants";
		$statement = $this->db->prepare($sql);
		$statement->execute();
		return $statement;
	}
	// function saveEventInclusion($buddyList, $event_id){
	// 	foreach ($buddyList as $account_id => $buddy_id){
	// 		$sql = "INSERT INTO event_inclusion (event_id, account_id, buddy_id)
	// 				VALUES (?, ?, ?)";
	// 				$statement = $this->db->prepare( $sql );
	// 				$data = array($event_id, $account_id, $buddy_id);
	// 				$statement->execute($data);
	// 			}
	// 	function deleteWish($wish_id){

	// 	$sql = "DELETE FROM wish_list WHERE wish_id = ?";
	// 	$data = array($wish_id);
	// 	$statement = $this->db->prepare($sql);
	// 	$statement->execute($data);

	// 	$sql = "DELETE FROM dibs WHERE wish_id = ?";
	// 	$data = array($wish_id);
	// 	$statement = $this->db->prepare($sql);
	// 	$statement->execute($data);
	// }


	function submitApplication($event_id, $user_id){
		$sql = "INSERT INTO event_applicants (event_id, applicant_id)
				VALUES (?, ?)";
		$statement = $this->db->prepare( $sql );
		$data = array($event_id, $user_id);
		$statement->execute($data);
	}
	function removeApplication($event_id, $user_id){
		$sql = "DELETE FROM event_applicants WHERE event_id = ? AND applicant_id = ?";
		$data = array($event_id, $user_id);
		$statement = $this->db->prepare($sql);
		$statement->execute($data);
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
			$this->makeHostsApply();
	}
	function makeHostsApply(){
		$sql = "SELECT *
				FROM event_info";
		$statement = $this->db->prepare($sql);
		$statement->execute();
		while($event = $statement->fetchObject()){
			$r = $this->checkForApplication($event->host_id, $event->event_id);
			if ($r !== "exists"){
				$this->submitApplication($event->event_id, $event->host_id);
			}
		}
	}
	function getAllHostLiveEvents($host_id){
		$sql = "SELECT *
				FROM event_info
				WHERE host_id = ?
				AND status = ?";
		$statement = $this->db->prepare($sql);
		$data = array( $host_id, "Live" );
		$statement->execute($data);	
		$output = "";
		while($event = $statement->fetchObject()){
			$event_id = $event->event_id;
			$datetime = date_create($event->event_datetime);
			$date = date_format($datetime, 'F jS Y');
			$time = date_format($datetime, 'g:ia');
			$allEventIncluded = $this->getEventInclusion($event_id);
			$allIncluded = "";
			while ($included = $allEventIncluded->fetchObject()){
				$profile = $this->getAccount2($included->account_id);
				$allIncluded .= "<li>$profile->full_name and their buddy is $included->buddy_id</li>";
			}
			$output .= "<h4>$event->event_name</h4>
						<p>Date:$date</p>
						<p>Time:$time</p>
						<p>Description:$event->description</p>
						<p>Members:</p>
						<ul>
							$allIncluded
						</ul>
						<br>
						";
			}
			if (empty($output)){
				$output = "I'm sorry, it look like there aren't any live events you are hosting";
			}
			return $output;
	}
	function getEventInclusion($event_id){
		$sql = "SELECT *
				FROM event_inclusion
				WHERE event_id = ?";
				$statement = $this->db->prepare($sql);
				$data = array( $event_id );
				$statement->execute($data);
				return $statement;
	}
	function getAllHostStagingEvents($host_id){
		$sql = "SELECT *
				FROM event_info
				WHERE host_id = ?
				AND status = ?";
		$statement = $this->db->prepare($sql);
		$data = array( $host_id, "staging" );
		$statement->execute($data);	
		$output = "";
		while($event = $statement->fetchObject()){
			$event_id = $event->event_id;
			$datetime = date_create($event->event_datetime);
			$date = date_format($datetime, 'F jS Y');
			$time = date_format($datetime, 'g:ia');
			$applicants = $this->getAllApplicants($event_id);
			$checkboxApplicants = $this->getCheckboxApplicants($applicants);
			$output .= "<form method='post' action='http://hayniehaven.com/index.php?page=hosting-event'>
						<h4>$event->event_name</h4>
						<p>Date:$date</p>
						<p>Time:$time</p>
						<p>Description:$event->description</p>
						<p>Applicants:</p>
						$checkboxApplicants
						<input type='hidden' name='event_id' value='$event_id' />
						<input type='submit' name='go-live' value='Go Live' />
						<input type='submit' name='delete' value='Delete Event' />
						

						</form>
						<br>
						";
			}
			if (empty($output)){
				$output = "I'm sorry, it look like you don't have any staging events you are hosting";
			}
			return $output;
	}
	function verifyGoLiveEvent($event_id, $allIncluded){
		$errorMessage = "";
		$allIncludedCount = count($allIncluded);
		if ($allIncludedCount < 2){
			$errorMessage = "<font color='red'>**You need to select at least two applicants to host a Secret Santa Event.</font>";
		}else{
			$errorMessage = "**Your event just went live!";
		}
		if ($errorMessage == "**Your event just went live!"){
			$this->goLive($event_id, $allIncluded);
			$this->deleteApplicants($event_id);
		}
		return $errorMessage;
	}
	function goLive($event_id, $allIncluded){
	$sql = "UPDATE event_info 
			SET status = ?
			WHERE event_id = ?";
	$statement = $this->db->prepare( $sql );
	$data = array('Live', $event_id);
	$statement->execute($data);
	$buddyList = $this->assignBuddies($allIncluded);
	$this->saveEventInclusion($buddyList, $event_id);
	}
	function deleteApplicants($event_id){
		$sql = "DELETE FROM event_applicants WHERE event_id = ?";
		$data = array($event_id);
		$statement = $this->db->prepare($sql);
		$statement->execute($data);
	}
	function deleteEvent($event_id){
		$sql = "DELETE FROM event_info WHERE event_id = ?";
		$data = array($event_id);
		$statement = $this->db->prepare($sql);
		$statement->execute($data);
		$this->deleteApplicants($event_id);
	}




// This is for reference only
	// function saveBio($account_id, $bio, $hobbies_Likes, $other){
	// 	$sql = "UPDATE account_info
	// 			SET bio = ?,
	// 			hobbies_likes = ?,
	// 			other = ?
	// 			WHERE account_id = ?";
	// 			$statement = $this->db->prepare($sql);
	// 			$data = array($bio, $hobbies_Likes, $other, $account_id);
	// 			$statement->execute($data);

	// }



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







	function getAllApplicants($event_id){
		$sql = "SELECT *
				FROM event_applicants
				WHERE event_id = ?";
		$statement = $this->db->prepare($sql);
		$data = array( $event_id);
		$statement->execute($data);	
		return $statement;

	}function getCheckboxApplicants($applicants){
		$checkboxApplicants = "";
		while($applicant = $applicants->fetchObject()){
			$account_id = $applicant->applicant_id;
			$account = $this->getAccount2($account_id);
			$checkboxApplicants .= "<input type='checkbox' name='allIncluded[]' value='$account->account_id' >$account->full_name<br>";
		}
		return $checkboxApplicants;
	}
	// function getCheckboxVariables(){
	// 	$allAccounts = $this->getAllAccounts();
	// 	$checkboxVariables = "";
	// 	while ($account = $allAccounts->fetchObject() ){
	// 		// if($account->admin == "1" ){
	// 		// }else{
	// 		$checkboxVariables .= "<input type='checkbox' name='allIncluded[]' value='$account->account_id' >$account->first_name $account->last_name:$account->user_name<br>";
	// 		// }
	// 	}
	// 	return $checkboxVariables;
	// }
}



