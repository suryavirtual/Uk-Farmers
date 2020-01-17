<?php
/**
* @package Redirect-On-Login (com_redirectonlogin)
* @version 3.5.1
* @copyright Copyright (C) 2008 - 2015 Carsten Engel. All rights reserved.
* @license GPL versions free/trial/pro
* @author http://www.pages-and-items.com
* @joomla Joomla is Free Software
*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemRedirectonlogin extends JPlugin{		

	//cant use onAfterIni because itemid is not readable
	function onAfterRender(){	
			
		$database = JFactory::getDBO();	
		$app = JFactory::getApplication();		
		$session_id = session_id();	
		
		//get config
		$rol_config = $this->get_config();		
				
		$buffer = JResponse::getBody();			
		if(strpos($buffer, '<span class="com_redirectonlogin_message">')){			
			$database->setQuery( "UPDATE #__redirectonlogin_sessions SET message='', message_type='' WHERE session_id='$session_id' ");
			$database->query();				
		}		
		
		//if user came from the login page after getting no menu access, but browsed to another page, clear the cookie			
		$option = JRequest::getVar('option', '');
		$view = JRequest::getVar('view', '');
		$task = JRequest::getVar('task', '');
		$ds = DIRECTORY_SEPARATOR;
		
		//frontend
		if($app->isSite()){	
			
			//if jevents is installed, include lang file
			if(file_exists(JPATH_ROOT.$ds.'components'.$ds.'com_jevents'.$ds.'jevents.php')){
				$lang = JFactory::getLanguage();
				$lang->load('com_jevents', JPATH_ROOT, null, false);
			}
			
			//if no access to menu item, and config is set to redirect to page, set session
			if(($option=='com_users' && $view=='login') || ($option=='com_comprofiler' && $task=='login') || ($option=='com_community' && $task=='frontpage')){			
				if(($rol_config['after_no_access_page']=='page' || $rol_config['after_no_access_page']=='pagerolno') && strpos($buffer, JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'))){
					$data = $app->getUserState('users.login.form.data', array());
					$return = $data['return'];					
					$app->setUserState("com_redirectonlogin.return_url_after_unauthorised_access", $return);
				}
			}			
			
		}	
		
		$return_url = $app->getUserState("com_redirectonlogin.return_url_after_unauthorised_access", '');
		if($return_url && !($option=='com_users' && $task=='user.login') && !($option=='com_users' && $view=='login') && !($option=='com_comprofiler' && $task=='login') && !($option=='com_community' && $task=='frontpage')){				
			$app->setUserState("com_redirectonlogin.return_url_after_unauthorised_access", '');
		}
			
	}	
	
	function onAfterInitialise(){			
		
		$app = JFactory::getApplication();
		$database = JFactory::getDBO();	
		$time = time();	

		//get session id
		$session_id = session_id();
		if(empty($session_id)){
			session_start();
			$session_id = session_id();	
		}
		
		//check if session is in database yet
		$database->setQuery("SELECT * "
		." FROM #__redirectonlogin_sessions "
		." WHERE session_id='$session_id' "	
		." LIMIT 1 "	
		);
		$rows = $database->loadObjectList();
		$unixtime = 0;		
		$url = 0;
		$message = 0;
		$logout = 0;		
		foreach($rows as $row){	
			$unixtime = $row->unixtime;			
			$url = $row->url;
			$message = $row->message;	
			$logout = $row->logout;		
		}
		
		if(!$unixtime){
			//session is not in table, so get it in there
			
			//insert					
			$database->setQuery( "INSERT INTO #__redirectonlogin_sessions SET session_id='$session_id', unixtime='$time' ");
			$database->query();	
			
		}else{
			//session is in table
			
			//update time
			$database->setQuery( "UPDATE #__redirectonlogin_sessions SET unixtime='$time' WHERE session_id='$session_id' ");
			$database->query();	
				
		}			
		
		if($message){
			$message_wrapped = '<span class="com_redirectonlogin_message">'.$message.'</span>';			
			//JError::raiseWarning(403, $message_wrapped);
			$app->enqueueMessage($message_wrapped, 'message');						
		}	
		
		if($url){		
			$database->setQuery( "UPDATE #__redirectonlogin_sessions SET url='' WHERE session_id='$session_id' ");
			$database->query();	
			$app->redirect($url);	
		}
				
	}
	
	function get_config(){	
			
		$database = JFactory::getDBO();			
		
		$database->setQuery("SELECT config "
		."FROM #__redirectonlogin_config "
		."WHERE id='1' "
		."LIMIT 1"
		);		
		$raw = $database->loadResult();		
		
		$params = explode( "\n", $raw);
		
		for($n = 0; $n < count($params); $n++){		
			$temp = explode('=',$params[$n]);
			$var = $temp[0];
			$value = '';
			if(count($temp)==2){
				$value = trim($temp[1]);				
			}							
			$config[$var] = $value;	
		}	
		
		//reformat redirect url	
		$config['opening_site_url'] = str_replace('[equal]','=',$config['opening_site_url']);	
		$config['url_registration'] = str_replace('[equal]','=',$config['url_registration']);		
				
		return $config;			
	}
	
	
	
}
?>