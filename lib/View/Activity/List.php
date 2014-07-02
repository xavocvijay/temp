<?php
namespace xsocialApp;

class View_Activity_List extends \View{
		

	function recursiveRender(){
		$this->addClass('activity');
		$this->js('reload')->reload();
		foreach ($this->model as $activity) {
			if($this->model['from_member_id']==$this->api->xsocialauth->model->id){
					// $edit_btn=$this->add('xsocialApp/View_Activity_EditActivity')->setModel($this->model);
					
					
					// $del_btn=$this->add('Button')->set('delete');
					// if($del_btn->isClicked()){
					// 	if($this->model->deleteActivity())

					// 		$this->js(null,$del_btn->js()->hide('slow'))->_selector('.activity')->trigger('reload')->execute();
					// }
			}

			$this->add('xsocialApp/View_Activity',array('activity_id'=>$this->model->id,'activity_array'=>$activity));
		}
		parent::recursiveRender();
	}
}