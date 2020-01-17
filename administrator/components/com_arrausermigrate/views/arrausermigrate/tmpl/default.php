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

	$document = JFactory::getDocument();
	$document->addStyleSheet("components/com_arrausermigrate/css/arra_admin_layout.css");
	$document->addStyleSheet("components/com_arrausermigrate/css/arra_about.css");
	$document->addStyleSheet("components/com_arrausermigrate/css/arra_statistics.css");
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/ajax.js");
?>
<form action="index2.php" method="post" name="adminForm"> 
	<div class="row-fluid">
    	<div class="span4 arra-tab">
        	<span class="tab-header"><?php echo JText::_('ARRA_STATISTICS'); ?></span>
			<?php echo $this->listUsers; ?>
        </div>
        
        <div class="span4 arra-tab">
        	<span class="tab-header"><?php echo JText::_('ARRA_USER_ACTIONS'); ?></span>
            <table>
			    <tr>
				   <td align="center" style="padding-bottom:5px;">
						 <!-- start menu --> 			 
						<a href="index.php?option=com_arrausermigrate&task=export&controller=export">
							<img src="components/com_arrausermigrate/images/icons/export_menu.png"
							alt="Export" align="middle" name="" border="0" title="Arra User Export" />
                            <button class="btn btn-warning" type="button"><?php echo JText::_('ARRA_USER_EXPORT_MENU'); ?></button>
						</a>
					</td>
					<td align="center" style="padding-bottom:5px;">	
						<a href="index.php?option=com_arrausermigrate&task=import&controller=import">
							<img src="components/com_arrausermigrate/images/icons/import_menu.png"
							alt="Import" align="middle" name="" border="0" title="Arra User Import" />
							<button class="btn btn-warning" type="button"><?php echo JText::_('ARRA_IMPORT_MENU'); ?></button>
						</a>
					</td>
					<td align="center" style="padding-bottom:5px;">	
						<a href="index.php?option=com_arrausermigrate&task=language&controller=language">
							<img src="components/com_arrausermigrate/images/icons/language_menu.png"
							alt="Language" align="middle" name="" border="0" title="Arra User Language"/>
							<button class="btn btn-warning" type="button"><?php echo JText::_('ARRA_LANGUAGE_MENU'); ?></button>
						</a>
					 </td>	
				</tr>
				<tr>
				    <td colspan="3">
						<div class="alert alert-info">
                        	<p class="text-error">For any questions please use our forum:</p>
                            <a href="http://www.joomlarra.com/joomla-extensions-help/" target="_blank">http://www.joomlarra.com/joomla-extensions-help/</a>
                        </div>
                        
						<div class="alert alert-info">
                        	<p class="text-error">ARRA User Import Export documentation:</p>
                        	<a href="http://www.joomlarra.com/joomla-3-x-user-import/" title="joomla 3.0 documentation" target="_blank">http://www.joomlarra.com/joomla-3-x-user-import/</a>
						</div>
                        
						<div class="alert alert-info">
							<p class="text-error">To report bugs use this link:</p>
                            <a href="http://www.joomlarra.com/joomla-extensions-help/forum/arra-user-migrate/report-bugs/" target="_blank">http://www.joomlarra.com/joomla-extensions-help/forum/arra-user-migrate/report-bugs/</a>
						</div>
                        
                        <div class="alert alert-info">
							<p class="text-error">Feature request link:</p>
                            <a href="http://www.joomlarra.com/joomla-extensions-help/forum/arra-user-migrate/feature-request/" target="_blank">http://www.joomlarra.com/joomla-extensions-help/forum/arra-user-migrate/feature-request/</a>
						</div>
                        
						<div class="alert alert-info">
                        	<p class="text-error">General Discussions:</p>
                        	<a href="http://www.joomlarra.com/joomla-extensions-help/forum/arra-user-migrate/general-discussions-about-component/" target="_blank">http://www.joomlarra.com/joomla-extensions-help/forum/arra-user-migrate/general-discussions-about-component/</a>
						</div>
					</td>
				</tr>						
			</table>
        </div>
        
        <div class="span4 arra-tab">
        	<span class="tab-header"><?php echo JText::_('ARRA_ABOUT'); ?></span>
            <?php echo $this->about; ?>
            <div class="clearfix"></div>
            <div class="well well-small">
            	<img src="components/com_arrausermigrate/images/icons/video.gif" style="vertical-align:top;" alt="video">&nbsp;&nbsp;&nbsp;
				 <a href="http://www.youtube.com/watch?v=_QbNrkXEa5k" target="_blank">Ho to migrate users to J! 3.0</a>				
				
				 <br/> <br/>
				 
				 <img src="components/com_arrausermigrate/images/icons/video.gif" style="vertical-align:top;" alt="video">&nbsp;&nbsp;&nbsp;
				 <a href="http://www.youtube.com/watch?v=TRBd3jsmnAc" target="_blank">How to import Joomla users</a>				
				 
				 <br/> <br/>
				 
				 <img src="components/com_arrausermigrate/images/icons/video.gif" style="vertical-align:top;" alt="video">&nbsp;&nbsp;&nbsp;
				 <a href="http://www.youtube.com/watch?v=LXQCbLA5ue4" target="_blank">How to export Joomla users</a>
            </div>
        </div>
    </div>
    
    <div class="row-fluid">
        <div class="span12 arra-footer navbar-inner">
			<?php echo JText::_("ARRA_POWER_BY")." "; ?> <a href="http://www.joomlarra.com" target="_blank">www.joomlarra.com</a>
    	</div>
	</div>
</form>