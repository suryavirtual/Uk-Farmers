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
 * View class for a list of Mb2portfolio.
 */
class Mb2portfolioViewProjects extends JViewLegacy
{
		
	protected $items;
	protected $pagination;
	protected $state;
    protected $params;
	protected $skill;

	/**
	 * Display the view
	 */
	public function display($tpl = null){
        $app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		
				      
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
		$this->skills = $this->get('Skills');
		$this->pagination = $this->get('Pagination');
        $this->params = $app->getParams('com_mb2portfolio');
		$this->uid = uniqid();
        
		
		foreach($this->items as $item)
		{			
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;			
		}
		
		
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {;
            throw new Exception(implode("\n", $errors));
        }
		
							
		
			
		// Get helper functions
		Mb2portfolioHelper::before_head(null, $this->params, array('mod_id'=>'', 'mode'=>'projects', 'carousel'=>0));	
			
		        
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
		$title	= null;
		

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('com_mb2portfolio_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
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

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}    
    	
}