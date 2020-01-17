<?php
/**
 * @version     1.0.0
 * @package     com_mb2portfolio
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Mariusz Boloz <mariuszboloz@gmail.com> - http://marbol2.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_mb2portfolio/assets/css/mb2portfolio.css');
?>


<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'project.cancel'){
            Joomla.submitform(task, document.getElementById('extrafield-form'));
        }
        else{
            
            if (task != 'project.cancel' && document.formvalidator.isValid(document.id('extrafield-form'))) {
                
                Joomla.submitform(task, document.getElementById('extrafield-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>









<form action="<?php echo JRoute::_('index.php?option=com_mb2portfolio&view=extrafield&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="extrafield-form" class="form-validate">
    <div class="row-fluid">
        
        
        
        
        
        
        	 
        <div class="span9 form-horizontal">
        
         
                    
                   <div class="control-group">
                       <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                       <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                    </div> 
                    
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
                    </div>         
                    
                                    
                    
                    
                
        
        
        
        
        
        
        
        
        </div>
        
        
        
        
        <div class="span3">
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
            <fieldset class="adminform">
            
          
            
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>         
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
			</div>
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
			</div>
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>


            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>