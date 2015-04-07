<?php
/**
 * Flood Control add-on
 *
 * @package SocialStrap add-on
 * @author Milos Stojanovic
 * @copyright 2014 interactive32.com
 */


$this->attach('hook_data_presavepost', 1, function($post) {

	require_once realpath(dirname(__FILE__)) . "/data.php";
	
	if (PLUGIN_FLOODCONTROL_POST_LIMIT <= 0) return;
	
	$limit_period = PLUGIN_FLOODCONTROL_POST_PERIOD;
	if (!in_array($limit_period, array('MINUTE', 'HOUR', 'DAY', 'WEEK', 'MONTH'))) $limit_period = 'HOUR';

	$current_user_id = Zend_Auth::getInstance()->hasIdentity() ? (int)Zend_Auth::getInstance()->getIdentity()->id : 0;
	
	$Posts = new Application_Model_Posts();
	
	$translator = Zend_Registry::get('Zend_Translate');
	
	// this will sycn php & mysql time
	$now = Application_Plugin_Common::now();
	
	$sql = "
		SELECT count(*)
		FROM posts
		WHERE author_id = {$current_user_id}
		AND created_on >= '{$now}' - INTERVAL 1 {$limit_period}
		";
	
	$result = (int)$Posts->getAdapter()->fetchOne($sql);
	
	if ($result == PLUGIN_FLOODCONTROL_POST_LIMIT) {
		
		// first warning & redirect
		Application_Plugin_Alerts::info($translator->translate('You are posting too quickly. Slow down.'), 'on');
		
	} elseif ($result > PLUGIN_FLOODCONTROL_POST_LIMIT) {
		
		// error & defer with redirect
		Application_Plugin_Alerts::error($translator->translate('You are posting too quickly. Slow down.'), 'off');
		Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')->gotoUrl('');
	}

});


$this->attach('hook_data_presavecomment', 1, function(&$content) {
	
	require_once realpath(dirname(__FILE__)) . "/data.php";
	
	if (PLUGIN_FLOODCONTROL_COMMENT_LIMIT <= 0) return;
	
	$limit_period = PLUGIN_FLOODCONTROL_COMMENT_PERIOD;
	if (!in_array($limit_period, array('MINUTE', 'HOUR', 'DAY', 'WEEK', 'MONTH'))) $limit_period = 'HOUR';

	$current_user_id = Zend_Auth::getInstance()->hasIdentity() ? (int)Zend_Auth::getInstance()->getIdentity()->id : 0;
	
	$Comments = new Application_Model_Comments();
	
	$translator = Zend_Registry::get('Zend_Translate');
	
	// this will sycn php & mysql time
	$now = Application_Plugin_Common::now();
	
	$sql = "
		SELECT count(*)
		FROM comments
		WHERE author_id = {$current_user_id}
		AND created_on >= '{$now}' - INTERVAL 1 {$limit_period}
		";
	
	$result = (int)$Comments->getAdapter()->fetchOne($sql);
	
	// error & clear comment content
	if ($result > PLUGIN_FLOODCONTROL_COMMENT_LIMIT) {
		Application_Plugin_Alerts::error($translator->translate('You are posting too quickly. Slow down.'), 'off');
		$content = '';
	}
});