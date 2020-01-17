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
	$document->addStyleSheet("components/com_arrausermigrate/css/arra_statistics_layout.css");
	$document->addStyleSheet("components/com_arrausermigrate/css/arra_import_layout.css");
	
	$search = JFactory::getApplication()->input->get("search", "", "raw");
	
	$find_users = array();
	if(trim($search) != ""){
		$find_users = $this->findUsers($search);
	}
?>

<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton){
		if(pressbutton == "delete"){
			if(confirm('<?php echo JText::_("ARRA_SURE_DELETE_USERS"); ?>')){
				submitform(pressbutton);
			}
			else{
				return false;
			}
		}
		else{
			submitform(pressbutton);
		}
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div class="row-fluid">
        <div class="alert alert-warning">
        	<img src="<?php echo JUri::base().'components/com_arrausermigrate/images/icons/notice_note.png'; ?>">
            <?php echo JText::_("ARRA_DELETE_USERS_NOTICE"); ?> <a href="http://www.joomlarra.com/joomla-1.7-user-export-import-documentation/delete-joomla-17-users.html" title="delete joomla 1.7 users" target="_blank">>> DOCUMENTATION</a>
        </div>
    </div>

	<div class="row-fluid">
    	<div class="span6">
        	<div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                    	<span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_USER_TYPE") . "::" .JText::_("ARRA_TOOLTIP_USERTYPE"); ?>" >
							<?php echo JText::_("ARRA_USER_TYPE"); ?>
                        </span>
                    </h3>
                </div>
                <?php echo $this->getUserType(); ?>
			</div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                    	<span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_REGISTER_DATE") . "::" .JText::_("ARRA_REGISTER_DATE"); ?>" >
							<?php echo JText::_("ARRA_REGISTER_DATE"); ?>
                        </span>
                    </h3>
                </div>
                <?php echo $this->getRegisterDate(); ?>
			</div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                    	<span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_VISIT_DATE") . "::" .JText::_("ARRA_VISIT_DATE"); ?>" >
							<?php echo JText::_("ARRA_VISIT_DATE"); ?>
                        </span>
                    </h3>
                </div>
                <?php echo $this->getLastVisitedDate(); ?>
			</div>
            <div class="row-fluid">
                <div class="span6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_ACTIVATION") . "::" .JText::_("ARRA_ACTIVATION"); ?>" >
                                    <?php echo JText::_("ARRA_ACTIVATION"); ?>
                                </span>
                            </h3>
                        </div>
                        <?php echo $this->getActivatedUsers(); ?>
                    </div>
                </div>
                <div class="span6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_BLOCK_UNBLOCK_USER") . "::" .JText::_("ARRA_BLOCK_UNBLOCK_USER"); ?>" >
                                    <?php echo JText::_("ARRA_BLOCK_UNBLOCK_USER"); ?>
                                </span>
                            </h3>
                        </div>
                        <?php echo $this->getBlockUnblock(); ?>
                    </div>
                </div>
			</div>
        </div>
        <div class="span6">
        	<div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_DELETE_BY_SEARCH_LEGEND") . "::" .JText::_("ARRA_DELETE_BY_SEARCH_LEGEND"); ?>" >
                            <?php echo JText::_("ARRA_DELETE_BY_SEARCH_LEGEND"); ?>
                        </span>
                    </h3>
                </div>
                <table>
                    <tr>
                        <td class="td_settings_options">
                        	<?php echo JText::_("ARRA_DELETE_BY_SEARCH"); ?>
                        </td>
                        <td>
                            <input type="text" name="search" value="<?php echo $search; ?>" style="margin-bottom:0px !important;"/>
                            <input type="submit" name="searc_button" value="<?php echo JText::_("ARRA_SEARCH_BUTTON"); ?>"/>
                        </td>
                    </tr>
                </table>
                <div id="table_find_users">
					<?php
						if(count($find_users) > 0){
					?>
							<table class="table table-striped">
								<tr>
									<th width="1%" align="center">#</th>			
									<th width="1%" align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($find_users); ?>);" /></th>
									<th width="10%" align="left"><?php echo JText::_("ARRA_NAME"); ?></th>
									<th width="5%" align="center"><?php echo JText::_("ARRA_USERNAME"); ?></th>
									<th width="5%" align="center"><?php echo JText::_("ARRA_EMAIL"); ?></th>
								</tr>
								<?php
									$j = 0;
									$k = 1;
									for($i=0; $i<count($find_users); $i++) {						
										$item = $find_users[$i];
								?>
										<tr class="<?php echo "row".$j; ?>">
											<td align="center"><?php echo $k; ?></td>
											<td align="center"><?php echo JHtml::_('grid.id', $i, $item["id"]); ?></td>
											<td align="left"><?php echo $item["name"]; ?></td>		
											<td align="left"><?php echo $item["username"]; ?></td>
											<td align="left"><?php echo $item["email"]; ?></td>
										</tr>
								<?php
										$k ++;
										$j = 1-$j;
									}
								?>
							</table>	
					<?php		
						}
					?>
				</div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_USERNAME_LIST_LEGEND") . "::" .JText::_("ARRA_USERNAME_LIST_LEGEND"); ?>" >
                            <?php echo JText::_("ARRA_USERNAME_LIST_LEGEND"); ?>
                        </span>
                    </h3>
                </div>
                <table width="100%">
                    <tr>
                        <td class="td_settings_options">
							<?php
                            echo JText::_("ARRA_SET_USERNAME_LIST");
                            ?>
                            <textarea name="username_list" rows="18"  wrap="off" style="width:95%;"></textarea>
                        </td>
                    </tr>
                </table>
			</div>
        </div>
    </div>

	<div id="statistic_result" style="display:none;"></div>
	
	<input type="hidden" name="option" value="com_arrausermigrate" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="delete" />
</form>