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
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/validations.js");
	$task = JFactory::getApplication()->input->get("task", "", "raw");
	
	if($task == "step3"){
?>

        <div class="progress progress-striped active">
            <div style="width: 100%;" id="quick-progress-final" class="bar bar-success"></div>
        </div>
        
        <table style="width: 100%; text-align: center;">
            <tr>
                <td style="font-size:20px;">
                    <?php
                        echo JText::_("ARRA_WHAIT_FOR_QUICK");
                    ?>
                </td>
            </tr>
        </table>
<?php
	}
	elseif($task == "step3_import"){
		$return_import = $this->return_import;
?>
		
        <input type="button" class="btn btn-primary" value="<?php echo JText::_("ARRA_BACK"); ?>" onclick="window.location='index.php?option=com_arrausermigrate'" style="margin:10px 0px;" />
        
        <div class="progress">
            <div style="width: 100%;" id="quick-progress-final" class="bar bar-success"></div>
        </div>
        
        <table style="width: 100%; text-align: center;">
            <tr>
                <td style="font-size:20px;">
                    <?php
                        echo JText::_("ARRA_IMPORTED_QUICK");
                    ?>
                </td>
            </tr>
        </table>
        
        <table style="border: 1px solid #ccc; margin: 30px auto; width: 40%;" cellpadding="10">
            <tr>
                <td style="font-size:19px; text-align: left;" width="90%">
                    <?php
                        echo JText::_("ARRA_TOTAL_USERS_IMPORTED");
                    ?>
                </td>
                <td style="font-size:19px;">
                	<?php
                    	echo $return_import["total_users"];
					?>
                </td>
            </tr>
            
            <tr>
                <td style="font-size:19px; text-align: left;" width="90%">
                    <?php
                        echo JText::_("ARRA_UPDATED_USERS");
                    ?>
                </td>
                <td style="font-size:19px;">
                	<?php
                    	echo $return_import["updated_users"];
					?>
                </td>
            </tr>
            
            <tr>
                <td style="font-size:19px; text-align: left;" width="90%">
                    <?php
                        echo JText::_("ARRA_NEW_ADDED_USERS");
                    ?>
                </td>
                <td style="font-size:19px;">
                	<?php
                    	echo $return_import["imported_users"];
					?>
                </td>
            </tr>
            
            <tr>
                <td style="font-size:19px; text-align: left;" width="90%">
                    <?php
                        echo JText::_("ARRA_CANT_ADDED_USERS");
                    ?>
                </td>
                <td style="font-size:19px;">
                	<?php
                    	echo $return_import["no_users"];
					?>
                </td>
            </tr>
        </table>
<?php	
	}
?>