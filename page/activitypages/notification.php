<?php

class page_xsocialApp_page_activitypages_notification extends Page{
	function init() {
		parent::init();

		$this->js(true,$this->js()->_selector('.notification')->trigger('reload'));
		$activity = $this->api->xsocialauth->model->getNotifications( false );
		foreach ( $activity as $junk ) {
			$v=$this->add('xsocialApp/View_Notifications_NotificationView')->set( $junk['name']);
			$btn=$v->add( 'Button',null,'button')->set( 'show me' );
			$act_id = $activity['related_activity_id']?:$activity->id;
			$btn->js( 'click' )->univ()->frameURL( 'Notification', $this->api->url( 'xsocialApp_page_activitypages_activity', array( 'activity_id'=>$act_id)));
		}
	}

}

