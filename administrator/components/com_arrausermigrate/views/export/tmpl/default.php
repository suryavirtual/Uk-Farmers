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
	$document->addStyleSheet("components/com_arrausermigrate/css/arra_export_layout.css");
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/validations.js");
?>

<form action="index.php" method="post" name="adminForm" id="adminForm"> 
	<div class="row-fluid">
    	<div class="span4">
        	<div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                    	<span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_SIMPLE_USER_EXPORT") . "::" .JText::_("ARRA_TOOLTIP_SIMPLE_USER_EXPORT"); ?>" >
							<?php echo JText::_("ARRA_SIMPLE_USER_EXPORT"); ?>
						</span>
                    </h3>
                </div>
				<table class="table-group">
					<tr>
						<td valign="top">				   				     
							<?php
                                echo $this->first_columns_export;
                            ?>
						</td>
                    </tr>
				</table>
                
                <table class="table-group">
                    <tr>
                    	<td valign="top">
							<table>
                              <tr>
                                 <td valign="top" class="td-radio-checkbox">
                                    <input type="checkbox" name="split_name">
                                 </td>
                                 <td valign="top" class="td_export_definitions">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_SPLIT_NAME") . "::" .JText::_("ARRA_TOOLTIP_SPLIT_NAME"); ?>">
                                        <?php echo JText::_("ARRA_SPLIT_NAME"); ?>
                                    </span>	 									    
                                 </td>
                              </tr>
                              <tr>
                                 <td valign="top" class="td-radio-checkbox">
                                    <input type="checkbox" name="remove_header">
                                 </td>												
                                 <td class="td_export_definitions">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_REMOVE_HEADER") . "::" .JText::_("ARRA_TOOLTIP_ARRA_REMOVE_HEADER"); ?>">
                                        <?php echo JText::_("ARRA_REMOVE_HEADER"); ?>
                                    </span>
                                 </td>                                             	                                           		
                               </tr>
                            </table> 	 	
                       </td>
                    </tr>
				</table>
                
                <table>
                    <tr>
                        <td>
                        	<input type="submit" class="btn btn-primary" name="export" value=" <?php echo JText::_("ARRA_EXPORT_BUTTON"); ?> ">										
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                    	<span class="editlinktip hasTip" title=" <?php echo JText::_("ARRA_ADDITIONAL_FIELDS_PROFILE")."::".JText::_("ARRA_TOOLTIP_FIELDS_PROFILE"); ?> " > 
							 <?php  echo JText::_("ARRA_ADDITIONAL_FIELDS_PROFILE"); ?> 
                        </span>
                    </h3>
                </div>
                <span style="font-size:14; font-family:Verdana, Arial, Helvetica, sans-serif; font-weight:bold; color:red; margin-left:5px;">
				<?php
                    echo JText::_("ARRA_JOOMLA_PROFILE_DETAILS");
                ?>
                </span>
                <table width="100%">
                    <tr>
                        <td width="100%">
                            <?php echo $this->user_profile_fields; ?>
                        </td>
                    </tr>
                </table>
			</div>
            
        </div>
        
        <div class="span8">
        	<div class="row-fluid">
            	<div class="span8">
                	<div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="editlinktip hasTip" title=" <?php echo JText::_("ARRA_FIELDS_TO_EXPORT")."::".JText::_("ARRA_TOOLTIP_FIELDS_TO_EXPORT"); ?> " > 
									 <?php  echo JText::_("ARRA_FIELDS_TO_EXPORT"); ?> 
                                </span>
                            </h3>
                        </div>
                        <table>
                            <tr>
                               <td width="60%">				   				     
                                <?php // second columns password/user type/blocked 
									echo $this->second_columns_export1;
								?>
                               </td>
                                <td width="60%" align="right" valign="top">		   				     
                                <?php // second columns register date/last visit/activation 
									echo $this->second_columns_export2;
								?>
                               </td>								  
                            </tr>											
                        </table>
					</div>
                </div>
                <div class="span4">
                	<div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_USER_TYPE")."::".JText::_("ARRA_TOOLTIP_USER_TYPE_PANEL"); ?>"> 
									 <?php  echo JText::_("ARRA_USER_TYPE"); ?> 
                                </span>
                            </h3>
                        </div>
                        <table>
							<tr>
                               	<td valign="top">
                                   	<?php // user type 
										echo $this->user_type;
									?>
                                </td>	 
                            </tr>
						</table>
					</div>
                </div>
            </div>
            <div class="row-fluid">
            	<div class="span12">
                	<div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="editlinktip hasTip" title=" <?php echo JText::_("ARRA_EXPORT_FILE_TYPE")."::".JText::_("ARRA_TOOLTIP_EXPORT_FILE_TYPE_PANEL");?>">
									<?php  echo JText::_("ARRA_EXPORT_FILE_TYPE"); ?> 
								</span>
                            </h3>
                        </div>
                        <table width="100%">
                            <tr>	
                            	<td colspan="2" align="left">
									<div id="file_type_id">
                            			<table width="100%">
                                        	<tr>
                                            	<td class="td_class" style="width:15%; vertical-align:top;">
                                                	<span class="editlinktip hasTip" title=" <?php echo JText::_("ARRA_SEPARATOR")."::".JText::_("ARRA_TIP_SEPARATOR") ?> ">
                                                    	<?php echo JText::_("ARRA_SEPARATOR");?>
                                                    </span>
                                                </td>
                                                <td style="vertical-align:top;" width="15%">
                            						<?php  echo $this->separators; ?> 
                            					</td>
                                            	<td class="td_class" style="vertical-align:top;" width="70%">
                                                	<span>
                            							<a style="color:red;" href="#" onclick="javascript:hide_show('separator_div')"><?php echo JText::_("ARRA_SEPARATOR_2_HEADER"); ?></a>
													</span>
                            						<div id="separator_div" style="display:none; color:red;">
														<?php echo JText::_("ARRA_SEPARATOR_2"); ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                            		</div>
                            		<div id="ordering_export">
                            			<table width="100%"> 
                            				<tr>
                                                <td class="td_class" width="15%">
													<span class="editlinktip hasTip" title=" <?php echo JText::_("ARRA_ORDERING")."::".JText::_("ARRA_TIP_ORDERING") ?> ">
                                                		<?php echo JText::_("ARRA_ORDERING");?>
                                                	</span>
                                                </td>
                            					<td width="85%">
                            						<?php echo $this->ordering; ?> 
                            					</td>
                            				</tr>
                            			</table>
                            		</div>
                            	</td>
                            </tr>
                            
                            <tr>
                                <td>
									<?php echo $this->file_type; ?>
                                </td>
                            </tr>													 								
                        </table>
					</div>
                </div>
            </div>
            
            <div class="row-fluid">
				<div class="span12">
                	<div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
								<span class="editlinktip hasTip" title=" <?php echo JText::_("ARRA_EXPORT_USER_TABLE")."::".JText::_("ARRA_TOOLTIP_EXPORT_USER_TABLE"); ?> " >
									<?php echo JText::_("ARRA_EXPORT_USER_TABLE"); ?>
								</span>
                            </h3>
                        </div>
                        <?php  echo $this->table_file_type; ?>
					</div>
				</div>
            </div>
            
            <div class="row-fluid">
				<div class="span12">
                	<div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
								<span class="editlinktip hasTip" title=" <?php echo JText::_("ARRA_SEND_TO_EMAIL").":".JText::_("ARRA_TOOLTIP_SEND_TO_EMAIL"); ?> " >
					      			<?php echo JText::_("ARRA_SEND_TO_EMAIL"); ?>
								</span>
                            </h3>
                        </div>
                        <table cellspacing="5">
							<?php echo $this->email_to; ?>
                         </table>
					</div>
				</div>
            </div>
            
        </div>
        
    </div>	
	  
    <input type="hidden" name="option" value="com_arrausermigrate" />
    <input type="hidden" name="task" value="export_file" />
    <input type="hidden" name="controller" value="export" />
</form>