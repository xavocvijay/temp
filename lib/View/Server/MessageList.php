<?php

namespace xsocialApp;

class View_Server_MessageList extends \View{
	function init(){
		parent::init();
			
			$this->api->stickyGET('member_id');
			if(!$this->api->xsocialauth->model->is_myFriend($_GET['member_id']))
					$this->api->redirect($this->api->url('index',array('subpage'=>'xsocial-dashboard')))->execute();				
			if($_GET['member_id']){
				$member=$this->add('xsocialApp/Model_MemberAll');
				$member->load($_GET['member_id']);
				$message=$member->ref('xsocialApp/Message');
				foreach ($message as $junk) {
					$message['is_read']=true;
					$message->save();
				}
			}

			$messagelist=$this->add('xsocialApp/View_Message_List');
			if(!$_GET['member_id']){
				$this->add('View_Info')->set('Please Select Friend');
				$messagelist->template->tryDel('text_message');
			}
	
			$messages=$this->add('xsocialApp/Model_Message');
			$messages->_dsql()->where(
				$messages->_dsql()->orExpr()
			->where('(message_from_member_id = '.$this->api->xsocialauth->model->id. ' and message_to_member_id ='.($_GET['member_id']?:0).')')
						->where('(message_to_member_id = '.$this->api->xsocialauth->model->id. ' and message_from_member_id ='.($_GET['member_id']?:0).')')
			);

			$messages->setOrder('id','asc');


	// $messages->addCondition('message_from_member_id',$this->api->xsocialauth->model->id);
	// $messages->addCondition('message_to_member_id',$this->api->xsocialauth->model->id);
	$messagelist->setModel($messages);

	$messagelist->template->tryDel('see_all');

	$form=$messagelist->add('Form',null,'form');
	$form->addField('line','message');

	if($form->isSubmitted()){
		$m=$this->add('xsocialApp/Model_Message');
		$m['message']=$form['message'];
		$m['message_from_member_id']=$this->api->xsocialauth->model->id;
		$m['message_to_member_id']=$_GET['member_id'];
		$m->save();

		$form->js(null,$messagelist->js()->reload())->reload()->execute();
	}




	}

	
}