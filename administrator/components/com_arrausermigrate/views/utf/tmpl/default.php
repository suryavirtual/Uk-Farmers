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
	$document->addStyleSheet("components/com_arrausermigrate/css/arra_import_layout.css");
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/ajax.js");
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/utf.js");
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/validations.js");
	
?>
<ul class="nav nav-tabs" id="myTab">
	<li class="active"><a href="#export" data-toggle="tab"><?php echo JText::_('ARRA_UTF_EXPORT_PANEL')?></a>
	<li><a href="#import" data-toggle="tab"><?php echo JText::_('ARRA_UTF_IMPORT_PANEL')?></a>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="export">	  
		<form action="index.php" method="post" name="adminForm1" id="adminForm1">
			<div class="row-fluid">
				<div class="span6">
					<textarea style="width:550px;" name="export_result" id="export_result" cols="90" rows="32" wrap="off"><?php if(isset($this->export_result)){ echo $this->export_result;} ?></textarea>
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3 class="panel-title">
								<span class="editlinktip hasTip" title=" <?php echo JText::_("ARRA_EXPORT_FILE_TYPE")."::".JText::_("ARRA_TOOLTIP_EXPORT_FILE_TYPE_PANEL");?>"> 
									<?php  echo JText::_("ARRA_EXPORT_FILE_TYPE"); ?> 
								</span>
							</h3>
						</div>
						<table>
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
									   <table> 
										   <tr>
											   <td class="td_class">
													<span class="editlinktip hasTip" title=" <?php echo JText::_("ARRA_ORDERING")."::".JText::_("ARRA_TIP_ORDERING") ?> ">
														   <?php echo JText::_("ARRA_ORDERING");?>
													</span>						
											  </td>
											  <td>
												  <?php  echo $this->ordering; ?> 
											  </td> 
										   </tr>
									   </table>
								   </div>
								</td>
							</tr>																			 								
						</table>
					</div>
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3 class="panel-title">
								<span class="editlinktip hasTip" title=" <?php echo JText::_("ARRA_SEND_TO_EMAIL").":".JText::_("ARRA_TOOLTIP_SEND_TO_EMAIL"); ?> " >
									<?php  echo JText::_("ARRA_SEND_TO_EMAIL"); ?>
								</span>
							</h3>
						</div>
						<table cellspacing="5">
							<?php echo $this->email_to; ?>
						</table>
					</div>
				</div>
				<div class="span6">
					<div class="row-fluid">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h3 class="panel-title">
									<span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_SIMPLE_USER_EXPORT") . "::" .JText::_("ARRA_TOOLTIP_SIMPLE_USER_EXPORT"); ?>" >
										<?php echo JText::_("ARRA_SIMPLE_USER_EXPORT"); ?>
									</span>
								</h3>
							</div>
							<?php echo $this->first_columns_export; ?>
							<table class="table-group">
								<tr>
									<td valign="top">
										<input type="checkbox" name="split_name">
									</td>
									<td valign="top" class="td_export_definitions">
										<span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_SPLIT_NAME") . "::" .JText::_("ARRA_TOOLTIP_SPLIT_NAME"); ?>">
										<?php echo JText::_("ARRA_SPLIT_NAME"); ?>
										</span>	 									    
									</td>
								</tr>
								<tr>
									<td valign="top">
										<input type="checkbox" name="remove_header">
									</td>												
									<td class="td_export_definitions">
										<span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_REMOVE_HEADER") . "::" .JText::_("ARRA_TOOLTIP_ARRA_REMOVE_HEADER"); ?>">
										<?php echo JText::_("ARRA_REMOVE_HEADER"); ?>
										</span>
									</td>                                             	                                           		
								</tr>
							</table>
							<input type="submit" class="btn btn-primary" name="export" value=" <?php echo JText::_("ARRA_EXPORT_BUTTON"); ?> ">
						</div>
					</div>
					<div class="row-fluid">
						<div class="span6">
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
									   <td width="55%">				   				     
										<?php // second columns password/user type/blocked 
											   echo $this->second_columns_export1; ?>
									   </td>
										<td width="50%" align="right" valign="top">		   				     
										<?php // second columns register date/last visit/activation 
											   echo $this->second_columns_export2; ?>
									   </td>								  
									</tr>
									<?php								
										if($this->second_columns_export3 != ""){
									?>	
									<tr>								  
										<td width="55%" align="left" valign="top">		   				     
										<?php // additional columns 
											 echo $this->second_columns_export3; ?>
									   </td>
									   <td width="50%" align="right" valign="top">
									   </td>								  
									</tr>	
									<?php	
										}
									?>
								</table>
							</div>
						</div>
						<div class="span6">
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
				</div>
			</div>
	  
			<input type="hidden" name="option" value="com_arrausermigrate" />
			<input type="hidden" name="task" value="export_file" />
			<input type="hidden" name="controller" value="utf" />
		</form>
	</div>
	
	<div class="tab-pane" id="import">	 		 
		<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" id="adminForm" style="width:100%;">
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
			
			<div class="row-fluid">
				<div class="span6">
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
					<textarea style="width:570px;" name="file_content" id="file_content" cols="105" rows="26" wrap="off"></textarea>
				</div>
				<div class="span6">
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
			
			<input type="hidden" name="back_up" value="" />				  
			<input type="hidden" name="option" value="com_arrausermigrate" />
			<input type="hidden" name="task" value="import_file" />
			<input type="hidden" name="controller" value="utf" />
		</form>
	</div>
</div>

