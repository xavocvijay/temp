<?php
namespace xsocialApp;

class View_ProfilePic extends \View{
	public $member_id=null;
	public $profile_pic_url=false;
	public $member=null;
	public $member_gender=null;
	function init(){
		parent::init();

		if($this->member_id===null AND $this->profile_pic_url===false AND $this->member == null)
			throw $this->exception('member_id / profile_pic_url or member object is not defined')->addMoreInfo("In View", $this->owner);
		if($this->profile_pic_url !==false){			
			if($this->profile_pic_url == false){
				$src='male.png';
				if($this->member_gender=='Female')				
					$src='female.png';
				// echo $this->profile_pic_url;
			}
			else
				$src=$this->profile_pic_url;
		}
		if($this->profile_pic_url ===false AND $this->member_id !=null ) {
			$this->member = $member=$this->add('xsocialApp/Model_Member');
			$member->load($this->member_id);
		}

		if($this->member){
				// throw new \Exception("Error Processing Request", 1);

			if($member['profile_pic']){
				$src=$member['profile_pic'];
			}elseif($this->member['gender']=='Female')
						$src='female.png';
			else
						$src="male.png";			
		}
		
		$this->setElement('img')->setAttr('src',$src)->addClass('profile_pic');
	}
} 