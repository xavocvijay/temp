<?php
namespace xsocialApp;
class Model_MemberAll extends \Model_Table{
	public $table="xsocialApp_members";
	function init(){
		parent::init();

		$this->addField('first_name');
		$this->addField('last_name');
		$this->addField('gender')->enum(array('Male','Female'));
		$this->addField('date_of_tbirth')->type('date');
		$this->addField('emailID')->hint('Used as your Username');
		$this->addField('password')->type('password');
		$this->addField('activation_code');
		$this->addField('last_notifiedID')->defaultValue(0);
		$this->addField('referId')->defaultValue(0); // Another member's ID used as its refID for others
		$this->addField('continues_days')->type('int')->defaultValue(0); // Another member's ID used as its refID for others
		$this->addField('is_verify')->type('boolean')->defaultValue(false);
		$this->addField('is_active')->type('boolean')->defaultValue(true);
		$this->addField('join_on')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('verified_on')->type('datetime')->defaultValue(null);

		$this->add('filestore/Field_Image','profile_pic_id');
		$this->add('filestore/Field_Image','timeline_pic_id');

		$this->hasMany('xsocialApp/ProfileFieldValues','member_id');

		$this->hasMany('xsocialApp/Activity','from_member_id',null,'MemberActivities');
		$this->hasMany('xsocialApp/Activity','related_member_id',null,'MemberRelatedActivities');
		$this->hasMany('xsocialApp/Friends','request_from_id',null,'RequestSent');
		$this->hasMany('xsocialApp/Friends','request_to_id',null,'RequestReceived');
		$this->hasMany('xsocialApp/Message','message_from_member_id');
		$this->hasMany('xsocialApp/Message','message_to_member_id');
		$this->hasMany('xsocialApp/PointTransaction','member_id');
		$this->hasMany('xsocialApp/MemberAttendanceLog','member_id');

		$this->addExpression('name')->set('concat(first_name," ",last_name)');

		$this->add('dynamic_model/Controller_AutoCreator');
		

	}

	

	function getAllFriends($return_query=false){

		$q='
			/* MAINE REQUEST BHEJI MATLAB MERA FRIEND*/
			(
				SELECT request_to_id friends_id from xsocialApp_friends WHERE request_from_id='.$this->id.' and is_accepted =1
			)
			/* USNE REQUEST BHEJI AUR MAINE ACCEPT KI TO WO MERA FRIEND*/
			union
			(
				SELECT request_from_id from xsocialApp_friends WHERE request_to_id='.$this->id.' AND is_accepted=1
			)
		';

		if($return_query) return $q;

		$friends = $this->api->db->dsql()->expr($q)->get();
		$friends_array=array(0);
		foreach ($friends as $fr) {
			$friends_array[] = $fr['friends_id'];
		}
		return $friends_array;
	}

	function is_myFriend($member_id){
		return in_array($member_id, $this->getAllFriends());
	}

	function getAllFriendsDetails(){

		$maine_request_bheji = $this->add('xsocialApp/Model_MemberAll');
		$maine_request_bheji->join('xsocialApp_friends.request_to_id')
							->addField('request_from_id');
		$maine_request_bheji->addCondition('request_from_id',$this->id);


		$usne_request_bheji_maine_acecpt_kari = $this->add('xsocialApp/Model_MemberAll');
		$x=$usne_request_bheji_maine_acecpt_kari->join('xsocialApp_friends.request_from_id');
											$x->addField('request_to_id');
											$x->addField('is_accepted');
		$usne_request_bheji_maine_acecpt_kari->addCondition('request_to_id',$this->id);
		$usne_request_bheji_maine_acecpt_kari->addCondition('is_accepted',true);

		$maine_request_bheji_array = $maine_request_bheji->getRows();
		$usne_request_bheji_maine_acecpt_kari_array = $usne_request_bheji_maine_acecpt_kari->getRows();

		return array_merge($maine_request_bheji_array,$usne_request_bheji_maine_acecpt_kari_array);

	}


	function getAllFollowers(){

	}

	function getAllFollowing(){
		$followings = $this->ref('RequestReceived')->addCondition('is_accepted',false);
		$followings_array= array();
		foreach ($followings as $follower) {
			$followings_array[] = $follower['request_from_id'];
		}
		return $followings_array;
	}

	function is_registered($userName){
		$member=$this->add('xsocialApp/Model_MemberAll');
		$member->addCondition('emailID',$userName);

		$member->tryloadAny();
		if($member->loaded()){
			return true;
		}else{
			return false;
		}
	}
	function has_referId($referanceId){
		$member=$this->add('xsocialApp/Model_MemberAll');
		$member->tryLoad($referanceId);
		if($member->loaded()){
			return true;
		}else{
			return false;
		}
	}


	function register($visitorInfo){
		$this['first_name']=$visitorInfo['first_name'];
		$this['last_name']=$visitorInfo['last_name'];
		$this['password']=$visitorInfo['password'];
		$this['emailID']=$visitorInfo['emailId'];
		$this['gender']=$visitorInfo['gender'];
		$this['date_of_birth']=$visitorInfo['DOB'];
		$this['referId']=$visitorInfo['referId'];
		if($this->save())
			return true;
		else
			return false;
			// throw new \Exception("Error Processing Request", 1);
											
	}

	function is_eligible($date1){

	$date2 = date('Y-m-d');
	$sec = strtotime($date2) - strtotime($date1);// == <seconds between the two times>
	$days = $sec / 86400;
	$years = $days / 365;
	return($years);
	// throw new \Exception($date1.'-'.$date2.'='.$sec."=days".$days."year".$years );
	}

/** RAKESH WORK */
	function sendVerificationMail(){
		if(!$this->loaded()) throw $this->exception('Model Must Be Loaded Before Email Send');

		$this['activation_code'] = rand(1000,99999);
		$this->save();
		$epan=$this->add('Model_Epan');
		$epan->tryLoadAny();
		
		$l=$this->api->locate('addons','xsocialApp', 'location');
			$this->api->pathfinder->addLocation(
			$this->api->locate('addons','xsocialApp'),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
			$tm=$this->add( 'TMail_Transport_PHPMailer' );
			$msg=$this->add( 'SMLite' );
			$msg->loadTemplate( 'mail/registrationVerifyMail' );

			//$msg->trySet('epan',$this->api->current_website['name']);		
			$enquiry_entries="some text related to register verification";
			$msg->trySetHTML('form_entries',$enquiry_entries);
			$msg->SetHTML('activation_code',$this['activation_code']);

			$email_body=$msg->render();	

			$subject ="Thank you for Registration.";

			try{
				$tm->send( $this['emailID'], $epan['email_username'], $subject, $email_body ,false,null);
				// throw new \Exception($this['emailID'].$epan['email_username'], 1);
				return true;
			}catch( phpmailerException $e ) {
				// throw $e;
				$this->api->js(null,'$("#form-'.$_REQUEST['form_id'].'")[0].reset()')->univ()->errorMessage( $e->errorMessage() . " " . "rksinha.btech@gmail.com"  )->execute();
			}catch( Exception $e ) {
				throw $e;
			}
	}

	function sendBirthdayWishMail(){
		if(!$this->loaded()) throw $this->exception('Model Must Be Loaded Before Email Send');

		$epan=$this->add('Model_Epan');
		$epan->tryLoadAny();
		
		$l=$this->api->locate('addons','xsocialApp', 'location');
			$this->api->pathfinder->addLocation(
			$this->api->locate('addons','xsocialApp'),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
			$tm=$this->add( 'TMail_Transport_PHPMailer' );
			$msg=$this->add( 'SMLite' );
			$msg->loadTemplate( 'mail/birthdaywish' );

			//$msg->trySet('epan',$this->api->current_website['name']);		
			$enquiry_entries="some text related to register verification";
			
			$email_body=$msg->render();	

			$subject ="Birthday wish.";

			try{
				$tm->send( $this['emailID'], $epan['email_username'], $subject, $email_body ,false,null);
				// throw new \Exception($this['emailID'].$epan['email_username'], 1);
				return true;
			}catch( phpmailerException $e ) {
				// throw $e;
				$this->api->js(null,'$("#form-'.$_REQUEST['form_id'].'")[0].reset()')->univ()->errorMessage( $e->errorMessage() . " " . "rksinha.btech@gmail.com"  )->execute();
			}catch( Exception $e ) {
				throw $e;
			}
	}

	
	function Verify($emailId,$activation_code){

		// throw new \Exception("$emailId", 1);
				
		$member=$this->add('xsocialApp/Model_MemberAll');
		$member->addCondition('emailID',$emailId);
		$member->addCondition('activation_code',$activation_code);
		$member->tryLoadAny();
		if($member->loaded()){
			$this->api->exec_plugins('OnVerificationRefeCheck',array($member->id,$member));
			$member['is_verify']= true;
			$this->api->exec_plugins('verifymember_register',array($member->id,$member));
			$member->save();

			return true;
		}
		else
			return false;
		
	}


	function is_current_user(){
		if($this->id == $this->api->xsocialauth->model->id)
			return true;
		else
			return false;
	}

	function getFriendStatus(){
		if(!$this->loaded()) throw $this->exception('Model Must be loaded to find friends status');

		$user = $this->api->xsocialauth->model;
		
		$received_request  = $this->ref('RequestSent')
						->addCondition('request_to_id',$user->id)
						->tryLoadAny();
		if($received_request->loaded() AND $received_request['is_accepted'])
			return "Friends";

		$sent_request  = $user->ref('RequestSent')
						->addCondition('request_to_id',$this->id)
						->tryLoadAny();

		if($sent_request->loaded() AND $sent_request['is_accepted'])
			return "Friends";

		if($received_request->loaded()  AND $received_request['is_accepted']==false)
			return "PendingApproval";

		if($sent_request->loaded() AND $sent_request['is_accepted'] == false)
			return "Following";

		return "NotFriends";

	}

	function sendFriendRequest(){
		$friend_request = $this->api->xsocialauth->model->ref('RequestSent');
		$friend_request['request_to_id'] = $this->id;
		$friend_request['send_on'] = date('Y-m-d H:i:s');
		$friend_request->save();
		return true;
	}
	function unFriend(){
		$friend_request_received = $this->api->xsocialauth->model->ref('RequestReceived')
														->addCondition('request_from_id',$this->id)
														->tryLoadAny();
		if($friend_request_received->loaded()) $friend_request_received->delete();

		$friend_request_sent=$this->api->xsocialauth->model->ref('RequestSent')
														->addCondition('request_to_id',$this->id)
														->tryLoadAny();
		if($friend_request_sent->loaded()) $friend_request_sent->delete();

	}
	function approveRequest(){
		$friend_request_received = $this->api->xsocialauth->model->ref('RequestReceived')
														->addCondition('request_from_id',$this->id)
														->tryLoadAny();
		$friend_request_received['is_accepted']=1;
		$friend_request_received['accepted_on']=date('Y-m-d H:i:s');
		$friend_request_received->save();
	}


	function linkfyText($text){
		$text= substr($text, 2, strpos($text, "/")-2);
		$linkfyText='<a href="?subpage=xsocial-profile&profile_of='.$this->api->cu_id.'">'.$text.'</a>';
		return $linkfyText;

	}

	function getNotifications($count=false){

		$user=$this->api->xsocialauth->model;
		$user->load($this->api->xsocialauth->model->id);

		$user_fiends=$user->getAllFriends();

		$me_and_myfriends=$user_fiends;
		$me_and_myfriends[]=$this->api->xsocialauth->model->id;

		$activity=$this->add('xsocialApp/Model_Activity');
		
		$activity->_dsql()
				 ->where(array(
						  array('from_member_id in ',$user_fiends),  
						  array('related_member_id in',$me_and_myfriends)
						  ));
		if($count){
			$activity->addCondition('id','>',$this['last_notifiedID']?:0);
			$max_activity_id = $this->add('xsocialApp/Model_Activity');
			$max_activity_id->setOrder('id','desc')->tryLoadAny();
			// throw new Exception("Error Processing Request", 1);
			
			return array(
						'count'=>$activity->count()->getOne(),
						'max_id' => $max_activity_id->id
					);
			
		}
		else
			return $activity;		 
		// throw new \Exception($this['last_notifiedID'], 1);
	}

	function getNotified($till_id){
		$this['last_notifiedID'] = $till_id;
		$this->save();
	}


	function updateProfilePic($details){
		$this['profile_pic_id']=$details['img_id'];

		$this->save();

		
		$activity=$this->add('xsocialApp/Model_Activity');

		$activity['name']=$this->linkfyText('{{'.$this->api->cu_name.'/'.$this->api->cu_emailid.'}} Updated Profile');
		$activity['from_member_id']=$this->id;
		$activity['activity_detail']='update profile pic';
		$activity['img_id']=$details['img_id'];
		$activity['visibility']=100;
		$activity['activity_type']='updateProfilePic';
		$activity->save();

		return true;

	}

	function updateCoverPage($details){
		// if($this->loaded()) throw $this->execption("New Post Can't be updated, It must be empty before save");
		$this['timeline_pic_id']=$details['img_id'];

		$this->save();

		
		$activity=$this->add('xsocialApp/Model_Activity');

		$activity['name']=$this->linkfyText('{{'.$this->api->cu_name.'/'.$this->api->cu_emailid.'}} Updated Cover Page');
		$activity['from_member_id']=$this->id;
		$activity['activity_detail']='update cover page';
		$activity['img_id']=$details['img_id'];
		$activity['visibility']=100;
		$activity['activity_type']='updateCoverPage';
		$activity->save();

		return true;

	}

}