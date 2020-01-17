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
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/statistics.js");	
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/GenGraphics.js");
	$document->addScript(JURI::base()."components/com_arrausermigrate/includes/js/graphicImage.js");
	
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">   
	<div class="row-fluid">
    	<div class="span6">
        	<div class="alert alert-info">
                <b style="color:#FF6D00;">
                    <?php echo "On the right hand side you will see the details (number and percentage) from the total amount of users." ?>
                </b>
            </div>
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
            	<div class="span4">
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
                <div class="span4">
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
                <div class="span4">
            		<div class="panel panel-info">
						<div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_SEND_EMAIL") . "::" .JText::_("ARRA_SEND_EMAIL"); ?>" >
                                    <?php echo JText::_("ARRA_SEND_EMAIL"); ?>
                                </span>
                            </h3>
                        </div>
                        <?php echo $this->getSendEmail(); ?>
                    </div>
            	</div>
            </div>
        </div>
        <div class="span6">
        	<table class="table table-striped">
                <tr>
                    <th class="td_class_statistics2"><?php echo JText::_("ARRA_USERS_NUMBER"); ?></th>
                    <th class="td_class_statistics2"><?php echo JText::_("ARRA_USERS_PERCENTE"); ?></th>
                    <th class="td_class_statistics2"><?php echo JText::_("ARRA_USERS_TOTAL"); ?></th>
                </tr>
                <tr>
                    <td class="td_class_statistics2"><div id="users_number"></div></td>
                    <td class="td_class_statistics2"><div id="users_percente"></div></td>
                    <td class="td_class_statistics2"><?php echo $this->users1; ?></td>	
                </tr>
            </table>
            <?php
				$usersCount = $this->countUsers();					
			?>
			<div id="image_diagram">
				<?php									
					foreach($usersCount as $key=>$value){
						echo $this->createDiagram($value["total"], $value);
					}							
				?>
			</div>
        </div>
    </div>

	<div id="statistic_result" style="display:none;"></div>
	
    <input type="hidden" name="option" value="com_arrausermigrate" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="statistics" />
</form>