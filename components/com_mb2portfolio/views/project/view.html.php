<?php

/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class Mb2portfolioViewProject extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;
    protected $params;
	
	protected $skill;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		$app= JFactory::getApplication();
		$doc = JFactory::getDocument();
        $user= JFactory::getUser();
		$model = $this->getModel();
        
        $this->state = $this->get('State');
        $this->item = $this->get('Data');
        $this->params = $app->getParams('com_mb2portfolio');
		$this->item->nextitem = $this->getModel()->item_navigation('next');
		$this->item->previtem = $this->getModel()->item_navigation('prev');
		
		
		// Get skills titles
		if (!empty($this->item)) 
		{			            
			$this->item->skill1_title = $this->getModel()->getSkillTitle($this->item->skill_1)->title;
			$this->item->skill_2 ? $this->item->skill2_title = $this->getModel()->getSkillTitle($this->item->skill_2)->title : $this->item->skill2_title = '';
			$this->item->skill_3 ? $this->item->skill3_title = $this->getModel()->getSkillTitle($this->item->skill_3)->title : $this->item->skill3_title = '';
			$this->item->skill_4 ? $this->item->skill4_title = $this->getModel()->getSkillTitle($this->item->skill_4)->title : $this->item->skill4_title = '';
			$this->item->skill_5 ? $this->item->skill5_title = $this->getModel()->getSkillTitle($this->item->skill_5)->title : $this->item->skill5_title = '';
			
			
			$this->item->skill1_alias = $this->getModel()->getSkillAlias($this->item->skill_1)->alias;
			$this->item->skill_2 ? $this->item->skill2_alias = $this->getModel()->getSkillAlias($this->item->skill_2)->alias : $this->item->skill2_alias = '';
			$this->item->skill_3 ? $this->item->skill3_alias = $this->getModel()->getSkillAlias($this->item->skill_3)->alias : $this->item->skill3_alias = '';
			$this->item->skill_4 ? $this->item->skill4_alias = $this->getModel()->getSkillAlias($this->item->skill_4)->alias : $this->item->skill4_alias = '';
			$this->item->skill_5 ? $this->item->skill5_alias = $this->getModel()->getSkillAlias($this->item->skill_5)->alias : $this->item->skill5_alias = '';
			
			
			
        }
		
		
		
		// Get related items
		$model = $this->getModel('project');
		$item_skills_arr = array($this->item->skill_1,$this->item->skill_2,$this->item->skill_3,$this->item->skill_4,$this->item->skill_5);
		$this->item->related_projects = $model->get_related_items($this->item->id, $item_skills_arr);
		
		
		
   		

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
		
		
		//run hits
		$model->hit();
		
        
        
        if($this->_layout == 'edit'){
            
            $authorised = $user->authorise('core.create', 'com_mb2portfolio');

            if ($authorised !== true) {
                throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }
		
			
		
		// Check if related projects are display within carousel
		$carousel = Mb2portfolioHelper::items_carousel($this->item->related_projects, $this->params, array('mode'=>'project-related'));
		
		
		// Get helper functions
		Mb2portfolioHelper::before_head($this->item, $this->params, array('mod_id'=>'', 'mode'=>'project', 'carousel'=>$carousel));
		
		
		
		
		
		//return error if id of project does not exists
		if(empty($this->item->id)){
			return JError::raiseError(404, JText::_('COM_MB2PORTFOLIO_ERROR_PROJECT_NOT_FOUND'));
		}
		
		
		       
        $this->_prepareDocument();

        parent::display($tpl);
    }


	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$pathway = $app->getPathway();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('COM_MB2PORTFOLIO_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		
		/*//set item title as page title
		if ($this->item->title){
			$title = $this->item->title;
		}*/
		
		
		
		
		$id = (int) @$menu->query['id'];
		
		// if the menu item does not concern this article
		if ($menu && ($menu->query['option'] != 'com_mb2portfolio' || $menu->query['view'] != 'project' || $id != $this->item->id)){
			// If this is not a single article menu item, set the page title to the article title
			if ($this->item->title)
			{
				$title = $this->item->title;
			}
			$path = array(array('title' => $this->item->title, 'link' => ''));
			
			$path = array_reverse($path);
			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}		
		
		
		
			
		
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);
		
		
		
		//get metadata
		$metadata = json_decode($this->item->metadata);	
		
		
		
		
		if(isset($metadata->description)){
			$this->document->setDescription($metadata->description);
		}
		elseif($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}		
		
		
		
		if(isset($metadata->keywords)){
			$this->document->setMetadata('keywords', $metadata->keywords);
		}
		elseif($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		
		
		
		
		if(isset($metadata->robots)){
			$this->document->setMetadata('robots', $metadata->robots);
		}
		elseif ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
		
		
		
		if (!empty($metadata))
		{	
		
			foreach ($metadata as $k => $v)
			{
				if ($v)
				{
					$this->document->setMetadata($k, $v);
				}
			}
		}
		
		
		
		
		
		
		
	}        
    
}
