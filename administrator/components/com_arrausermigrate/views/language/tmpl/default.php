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
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">   
    <div class="row-fluid">
    	<div class="span12">
        	<div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_LANGUAGE_PANEL") . "::" .JText::_("ARRA_LANGUAGE_PANEL"); ?>" >
                            <?php echo JText::_("ARRA_LANGUAGE_PANEL"); ?>
                        </span>
                    </h3>
                </div>
                <textarea style="width:100% !important;" rows="25" name="language_file"><?php echo $this->language_file ?></textarea>
            </div>
        </div>
    </div>
    <input type="hidden" name="option" value="com_arrausermigrate" />
    <input type="hidden" name="task" value="language" />
    <input type="hidden" name="controller" value="language" />
</form>