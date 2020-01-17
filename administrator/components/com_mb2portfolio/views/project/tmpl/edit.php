<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'project.cancel' || document.formvalidator.isValid(document.id('project-form'))) {
			Joomla.submitform(task, document.getElementById('project-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_mb2portfolio&layout=edit&id='.(int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="project-form" class="form-validate">
	<div class="row-fluid">
    
    
    	
    
    
    
    
		<div class="span9 form-horizontal">
        
        
        
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
        
        
        
        	 
			 
			 
			 <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_MB2PORTFOLIO_TAB_GENERAL', true)); ?>
             	
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
                </div>
                
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('skill_1'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('skill_1'); ?></div>
                </div>
                <div class="control-group">
                     <div class="control-label"><?php echo $this->form->getLabel('skill_2'); ?></div>
                     <div class="controls"><?php echo $this->form->getInput('skill_2'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('skill_3'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('skill_3'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('skill_4'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('skill_4'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('skill_5'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('skill_5'); ?></div>
                </div>
                
                
                
                
                
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('title_link'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('title_link'); ?></div>
                </div>
                
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('layout'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('layout'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('media_width'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('media_width'); ?></div>
                </div>            
             
             
             <?php echo JHtml::_('bootstrap.endTab'); ?>
             
             
             
             
             
             
             
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'description', JText::_('COM_MB2PORTFOLIO_TAB_DESCRIPTION', true)); ?>
             	
               
               <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('intro_text'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('intro_text'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('full_text'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('full_text'); ?></div>
                </div>
               
               
                 
             
             
            <?php echo JHtml::_('bootstrap.endTab'); ?>
             
             
             
             
             
             
             
             <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_MB2PORTFOLIO_TAB_IMAGES', true)); ?>
             	
                 
				 
				 <?php foreach ($this->form->getGroup('images',true) as $field) { ?>
					<div class="control-group">
						<?php if (!$field->hidden){ ?>
							<div class="control-label"><?php echo $field->label; ?></div>
						<?php } ?>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
                <?php } //end foreach ?> 
                
                
             
             <?php echo JHtml::_('bootstrap.endTab'); ?>
             
             
             
             
             
             <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'video', JText::_('COM_MB2PORTFOLIO_TAB_VIDEO', true)); ?>
             	
               <?php foreach ($this->form->getGroup('video',true) as $field) { ?>
					<div class="control-group">
						<?php if (!$field->hidden){ ?>
							<div class="control-label"><?php echo $field->label; ?></div>
						<?php } ?>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
                <?php } //end foreach ?> 
             
             
             <?php echo JHtml::_('bootstrap.endTab'); ?>
             
             
             
             
             
             
             
             
             
             
             
             <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'extra_fields', JText::_('COM_MB2PORTFOLIO_TAB_EXTRA_FIELDS', true)); ?>
             	
               	   
                    <?php foreach ($this->form->getGroup('extra_fields',true) as $field) { ?>
                        <div class="control-group">
                            <?php if (!$field->hidden){ ?>
                                <div class="control-label"><?php echo $field->label; ?></div>
                            <?php } ?>
                            <div class="controls">
                                <?php echo $field->input; ?>
                            </div>
                        </div>
                    <?php } //end foreach ?> 
                    
                
                
             
             
             <?php echo JHtml::_('bootstrap.endTab'); ?>
             
             
             
             
             
             
             
             
             
             
             
             
             
             
             <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'links', JText::_('COM_MB2PORTFOLIO_TAB_LINKS', true)); ?>
             	
               	   
                    <?php foreach ($this->form->getGroup('links',true) as $field) { ?>
                        <div class="control-group">
                            <?php if (!$field->hidden){ ?>
                                <div class="control-label"><?php echo $field->label; ?></div>
                            <?php } ?>
                            <div class="controls">
                                <?php echo $field->input; ?>
                            </div>
                        </div>
                    <?php } //end foreach ?> 
                    
                
                
             
             
             <?php echo JHtml::_('bootstrap.endTab'); ?>
             
             
             
             
             
             
             
             
             
              <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'metadata', JText::_('COM_MB2PORTFOLIO_TAB_METADATA', true)); ?>
             	
                 
				 
				 <?php foreach ($this->form->getGroup('metadata',true) as $field) { ?>
					<div class="control-group">
						<?php if (!$field->hidden){ ?>
							<div class="control-label"><?php echo $field->label; ?></div>
						<?php } ?>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
                <?php } //end foreach ?> 
                
                
             
             <?php echo JHtml::_('bootstrap.endTab'); ?>
             
             
             
             
             
             
             
             
             
             
             
        
        
        
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        
        
        
        
        
        
        </div>
        
        
        
        <div class="span3">
        
        
           <fieldset class="adminform">
        
        
        
        
        
        
        
        
        
        
         

			<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
			</div>
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('modified'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
			</div>
            
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>
            
             <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('modified_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
			</div>  
            
            
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('hits'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('hits'); ?></div>
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
				<div class="control-label"><?php echo $this->form->getLabel('checked_out_time'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('checked_out_time'); ?></div>
			</div>
            
            
				
            </fieldset>
    	</div>
        
        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
        
    </div>
</form>