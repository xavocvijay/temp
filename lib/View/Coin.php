<?php

namespace xsocialApp;

class View_Coin extends \View{

function init(){
	parent::init();

	$points=$this->api->xsocialauth->model->ref('xsocialApp/PointTransaction')->sum('points');
		
	if($points > '0')
		$this->add('View')->set($points)->setStyle('background','#A81010');
	else	
		$this->add('View')->set($points);
		
	}
}
