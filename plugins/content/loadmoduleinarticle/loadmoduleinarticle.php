<?php
/**
* @package plugin load module into article
* @version 3.1.0
* @copyright Copyright (C) 2008 - 2015 Carsten Engel. All rights reserved.
* @license GPL
* @author http://www.pages-and-items.com
*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentLoadModuleInArticle extends JPlugin{	


	public function onContentPrepare($context, &$article, &$params, $limitstart=0){		
		
		
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}
	  
		$regex = '/{(module)\s*(.*?)}/i';	
			
		$matches = array();
		$preg_set_order = PREG_SET_ORDER;
		preg_match_all($regex, $article->text, $matches, $preg_set_order);  
		
		$default_style = $this->params->def('style', 'none');
		
		foreach ($matches as $match){   		
			$module = '';
			$arguments = array();   		
			preg_match_all('/\[.*?\]/', $match[2], $arguments);		
			if ($arguments){
				foreach ($arguments as $i=>$argument){
					$module = preg_replace("/\[|]/", '', $argument);
				}
			}				
			
			$paramsarray = explode('|',$module[0]);			
			
			$module_id = $paramsarray[0];
			$module_style = $default_style;
			if(isset($paramsarray[1])){
				if($paramsarray[1]=='xhtml' || $paramsarray[1]=='rounded' || $paramsarray[1]=='none'){
					$module_style = $paramsarray[1];
				}
			}			
			$module_class = 0;	
			
			$show = 0;
			if($this->params->def('displayunpublished', '1')){
				$show = 1;
			}else{
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->select('published');
				$query->from('#__modules');
				$query->where('id='.$db->q($module_id));				
				$rows = $db->setQuery($query);				
				$rows = $db->loadObjectList();					
				foreach($rows as $row){	
					if($row->published){	
						$show = 1;
					}
				}
			}	
			
			if($show){	
				$module_output = $this->load_module($module_id, $module_class, $module_style);						
				$article->text = preg_replace($regex, $module_output, $article->text, 1);	
			}else{
				if(!$this->params->def('tags', '1')){
					$article->text = preg_replace($regex, '', $article->text, 1);
				}
			}
		} 		
	}
	
	protected function load_module($module_id, $module_class, $module_style){
		
		$db = JFactory::getDBO();
		$document = JFactory::getDocument();
		$renderer = $document->loadRenderer('module');
		
		$params	= array('style'=>$module_style);
		
		$contents = '';
		$module = 0;
		
		//get module as an object		
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__modules');
		$query->where('id='.$db->q($module_id));		
		$rows = $db->setQuery($query);				
		$rows = $db->loadObjectList();
			
		foreach($rows as $row){		
			//just to get rid of that stupid php warning
			$row->user = '';				
			$params = array('style'=>$module_style);			
			$contents = $renderer->render($row, $params);
		}
		
		return $contents;
	}
}

?>