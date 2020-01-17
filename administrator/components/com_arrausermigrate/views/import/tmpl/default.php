<?php 
/**
 * ARRA User Export Import component for Joomla! 1.6
 * @version 1.6.0
 * @author ARRA (joomlarra@gmail.com)
 * @link http://www.joomlarra.com
 * @Copyright (C) 2010 - 2011 joomlarra.com. All Rights Reserved.
 * @license GNU General Public License version 2, see LICENSE.txt or http://www.gnu.org/licenses/gpl-2.0.html
 * PHP code files are distributed under the GPL license. All icons, images, and JavaScript code are NOT GPL (unless specified), and are released under the joomlarra Proprietary License, http://www.joomlarra.com/licenses.html
 *
 * file: default.php
 *
 **** class     
 **** functions
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

	JHtml::_('behavior.tooltip');
	JHTML::_('behavior.modal');
	JHtml::_('behavior.formvalidation');
	JHtml::_('bootstrap.tooltip');
	JHtml::_('behavior.multiselect');
	JHtml::_('dropdown.init');
	JHtml::_('formbehavior.chosen', 'select');
	JHtml::_('bootstrap.framework');

	$document = JFactory::getDocument();
	$document->addStyleSheet("components/com_arrausermigrate/css/arra_admin_layout.css");
	$document->addStyleSheet("components/com_arrausermigrate/css/arra_import_layout.css");
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/ajax.js");
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/validations.js");	     
?>

<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" id="adminForm" style="width:100%;">
    <div class="row-fluid">
    	<div class="span12" style="text-align:right;">
        	<a class="modal link_arra_video"  rel="{handler: 'iframe', size: {x: 740, y: 425}}" style="font-size: 16px;" target="_blank" 
                href="index.php?option=com_arrausermigrate&controller=export&task=video&id=36138279">
                <img src="<?php echo JURI::base(); ?>components/com_arrausermigrate/images/icons/icon_video.gif" class="video_img" />
                <?php echo JText::_("ARRA_VIDEO_IMPORT"); ?>
            </a>
        </div>
    </div>
    
    <div class="row-fluid">
        <div class="alert alert-info">
            <img src="<?php echo JUri::base().'components/com_arrausermigrate/images/icons/notice_note.png'; ?>">
            <?php echo JText::_("ARRA_ADMIN_USERS_NOTICE"); ?><br />
            
            <fieldset class="radio btn-group" id="super_admin_users">
                <input type="radio" value="1" name="super_admin_users" id="jform_super_admin_users1">
                <label for="jform_super_admin_users1" class="btn"><?php echo JText::_("JYES"); ?></label>
                
                <input type="radio" value="0" name="super_admin_users" id="jform_super_admin_users0" checked="checked">
                <label for="jform_super_admin_users0" class="btn"><?php echo JText::_("JNO"); ?></label>
            </fieldset>
        </div>
	</div>
	
    <?php
    	$display = "none";
		
		$session = JFactory::getSession();
		$registry = $session->get('registry');
		$link_eror = $registry->get('link_eror', "");
		$error_empty_column = $registry->get('error_empty_column', "");
		$username_error = $registry->get('username_error', "");
		
		if(isset($link_eror) && $link_eror == "error"){
			$display = "block";
			$registry->set('link_eror', "");
	   }
	   elseif(isset($error_empty_column) && $error_empty_column == "error_empty_column"){
			$display = "block";
			$registry->set('error_empty_column', "");
	   }
	   elseif(isset($username_error) && $username_error == "error"){
			$display = "block";
			$registry->set('username_error', "");
	   }
	   else{
		   $display = "none";
	   }
	   
	   if($display == "block"){
	?>    
            <div class="row-fluid">
                <div class="span12">
                    <div id="error_fieldset" class="alert alert-error">
                        <?php echo $this->error_message; ?>
                    </div>
                </div>
            </div>
    <?php
    	}
	?>
    
    <div class="row-fluid">
    	<div class="span4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="editlinktip hasTip" title="<?php JHTML::_('behavior.tooltip'); echo JText::_("ARRA_CSV_TXT_PANEL_IMPORT")."::". JText::_("ARRA_TIP_CSV_TXT_PANEL_IMPORT") ; ?>" >
                               <?php  echo JText::_("ARRA_CSV_TXT_PANEL_IMPORT"); ?>
                         </span>
                    </h3>
                </div>
                <?php echo $this->upload_file_csv_txt; ?>
                <span style="color:#FF0000;"><b>	
					<?php  echo JText::_("ARRA_UPLOAD_FILE_LIMIT_SIZE"); ?>
					<?php  echo @ini_get('upload_max_filesize')."B"; ?>	</b>
				</span>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="editlinktip hasTip" title="<?php JHTML::_('behavior.tooltip'); echo JText::_("ARRA_SQL_ZIP_PANEL_IMPORT") ."::". JText::_("ARRA_TIP_SQL_ZIP_PANEL_IMPORT") ; ?>" >
							   <?php  echo JText::_("ARRA_SQL_ZIP_PANEL_IMPORT"); ?>
                         </span>
                    </h3>
                </div>
                <?php echo $this->upload_file_sql_zip; ?>
                <span style="color:#FF0000;"><b>	
					<?php  echo JText::_("ARRA_UPLOAD_FILE_LIMIT_SIZE"); ?>
                    <?php  echo @ini_get('upload_max_filesize')."B"; ?>	</b>
                </span>
            </div>
		</div>
        <div class="span8">
        	<div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="editlinktip hasTip" title="<?php JHTML::_('behavior.tooltip'); echo JText::_("ARRA_OPTIONS_FOR_EXISTING_USERS_PANEL") . "::".JText::_("ARRA_TOOLTIP_OPTIONS_FOR_EXISTING_USERS_PANEL"); ?>" >
							<?php  echo JText::_("ARRA_OPTIONS_FOR_EXISTING_USERS_PANEL"); ?>
                        </span>
                    </h3>
                </div>
                <?php echo $this->allSettings; ?>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="editlinktip hasTip" title="<?php JHTML::_('behavior.tooltip'); echo JText::_("ARRA_OPTIONS_FOR_CONTACT_PANEL") . "::".JText::_("ARRA_TOOLTIP_OPTIONS_FOR_CONTACT_PANEL"); ?>" >
                        	<?php  echo JText::_("ARRA_OPTIONS_FOR_CONTACT_PANEL"); ?>
                        </span>
                    </h3>
                </div>
                <?php echo $this->contactsComponent(); ?>
            </div>
        </div>
	</div>
    
	<div class="row-fluid">
		<div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="editlinktip hasTip" title="<?php JHTML::_('behavior.tooltip'); echo JText::_("ARRA_EMAILS_OPTIONS_PANEL")."::".JText::_("ARRA_TOOLTIP_EMAILS_OPTIONS_PANEL"); ?>" >
                    	<?php  echo JText::_("ARRA_EMAILS_OPTIONS_PANEL"); ?>
                    </span>
                </h3>
            </div>
            <?php echo $this->emailSettings; ?>
        </div>
	</div>

	<input type="hidden" name="file_import" value="" />	
    <input type="hidden" name="back_up" value="" />				  
	<input type="hidden" name="option" value="com_arrausermigrate" />
	<input type="hidden" name="task" value="import_file" />
	<input type="hidden" name="controller" value="import" />
</form>