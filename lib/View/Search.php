<?php

namespace xsocialApp;

class View_Search extends \View{

function init(){
	parent::init();

	$search_member = $this->add('xsocialApp/Model_MemberAll');

	// $search_member->addExpression('friend_point')->set(function($m,$q){
	// 	$fl1_rf_j = $m->leftJoin('xsocialApp_friends','request_from_member_id',null,'fl1_rf');
	// 	$fl1_rf_j->addField('request_to_member_id');
		
	// });

	// $search_member->setOrder('points');


	$form=$this->add('Form');
   	$form_field=$form->addField('autocomplete1/Basic','search');
   	$form_field->setModel($search_member);
	$form->template->tryDel('button_row');
	// $form_field->options += array('change'=>$form->js()->univ()->alert($form_field->js()->val())->_enclose());

	// $form->js()->disableEnter();
	// $form_field->js()->univ()->onKey('13',$form->js()->submit());

   	if($form->isSubmitted()){
   		// $member=$this->add('xsocialApp/Model_MemberAll');

   		// $member->load($form['search']);

   	// ?subpage=xsocial-profile&profile_of=11

   		$form->api->redirect($this->api->url(null,array('subpage'=>'xsocial-profile','profile_of'=>$form['search'])));
   		// $this->js()->reload(array(''))->execute();
   	}
	
	}
}
