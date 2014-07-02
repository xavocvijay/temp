<?php

namespace xsocialApp;

class View_Photo_List extends \CompleteLister{


	function formatRow(){
		$this->current_row_html['url']=$this->api->url(null,array('subpage'=>'xsocial-photos'));
	}
	

	
	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
		return array('view/xsocial-photolist');
	}


}

