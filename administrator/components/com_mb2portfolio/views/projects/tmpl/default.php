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

//image resize scripts
require JPATH_COMPONENT .'/libs/image_resize_class.php';
require JPATH_COMPONENT .'/libs/image_resize.php';
require JPATH_COMPONENT .'/libs/functions.php';

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('bootstrap.tooltip');	
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.multiselect');


// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_mb2portfolio/assets/css/mb2portfolio.css');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_mb2portfolio');
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_mb2portfolio&task=projects.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'projectList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_mb2portfolio&view=projects'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
    
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>        
		<div class="clearfix"> </div>
		<table class="table table-striped" id="projectList">
			<thead>
				<tr>
                <?php if (isset($this->items[0]->ordering)): ?>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
                <?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
                <?php if (isset($this->items[0]->state)): ?>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
                    
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_MB2PORTFOLIO_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
              
                
                <th class='left hidden-phone center'>
				<?php echo JText::_('COM_MB2PORTFOLIO_FEATURED_IMAGE'); ?>
				</th>
                
                
                
                
                <th class='left hidden-phone center'>
					<?php echo JHtml::_('grid.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
                
                
                <th class='left hidden-phone center'>
				<?php echo JHtml::_('grid.sort',  'JDATE', 'a.created', $listDirn, $listOrder); ?>
				</th>
                
                
                
                
                <th class='left hidden-phone center'>
				<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
				</th>
                
                
                
                
                <th class='left hidden-phone center'>
				<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
				</th>
                
                
                
                <th class='left hidden-phone center'>
				<?php echo JHtml::_('grid.sort',  'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
				</th>
                
                
                    
                    
                <?php if (isset($this->items[0]->id)): ?>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
                <?php endif; ?>
				</tr>
			</thead>
			<tfoot>
                <?php 
                if(isset($this->items[0])){
                    $colspan = count(get_object_vars($this->items[0]));
                }
                else{
                    $colspan = 10;
                }
            ?>
			<tr>
				<td colspan="<?php echo $colspan ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'a.ordering');
                $canCreate	= $user->authorise('core.create',		'com_mb2portfolio');
                $canEdit	= $user->authorise('core.edit',			'com_mb2portfolio');
                $canCheckin	= $user->authorise('core.manage',		'com_mb2portfolio');
                $canChange	= $user->authorise('core.edit.state',	'com_mb2portfolio');
				?>
				<tr class="row<?php echo $i % 2; ?>">
                    
                <?php if (isset($this->items[0]->ordering)): ?>
					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
                <?php endif; ?>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
                <?php if (isset($this->items[0]->state)): ?>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'projects.', $canChange, 'cb'); ?>
					</td>
                <?php endif; ?>
                    
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'projects.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_mb2portfolio&task=project.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->title); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->title); ?>
				<?php endif; ?>
                
                <span class="small">(<?php echo $item->alias; ?>)</span>
                
               
                
                <p class="small">
                <?php 
					
					
					echo JText:: _('COM_MB2PORTFOLIO_SKILLS').': ';
					
					$skills = com_mb2portfolio_skill_titles($item->skill_1,$item->skill_2,$item->skill_3,$item->skill_4,$item->skill_5);
					$i = 0;
					$count = count($skills);
					foreach($skills as $skill){
						
						$i++;
						
						if($i < $count){
							if ($canEdit){
								echo '<a href="'.JRoute::_('index.php?option=com_mb2portfolio&task=skill.edit&id='.$skill->id.'').'">'.$skill->title.'</a>, ';	
							}
							else{
								echo ''.$skill->title.', ';	
							}
							
						}
						else{
							if ($canEdit){
								echo '<a href="'.JRoute::_('index.php?option=com_mb2portfolio&task=skill.edit&id='.$skill->id.'').'">'.$skill->title.'</a>';	
							}
							else{
								echo $skill->title;	
							}
						}
						
					}
					
										
					?>
                    
                    </p>
                
                
                
                
                
                
                
				</td>
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
				<td class="hidden-phone center">
					<?php 
					
					$default_url = ''.JURI::base().'components/com_mb2portfolio/assets/images/default-thumbnail.png';
					$images = json_decode($item->images);

					
					$url = $images->featured_image;
					
					
					if(empty($url)){
						//$url = $default_url;
                                                 $url = ''.JURI::root().''.$images->featured_image.'';
                                              	
					}
					else{
						$url = ''.JURI::root().''.$images->featured_image.'';
                                               
					}					
					
                                        
					$id = $item->id;					
					
				        com_mb2portfolio_thumbnail($id,$url,68,68,100);
					
					
					?>
				</td>
                
                
                
                
                
                <td class="hidden-phone center">
				
					
					<?php 
					
					$current_user = $user->id;
					
					
					$is_admin = $user->authorise('core.admin');
						
					
					
					
					if($current_user === $item->created_by || $is_admin){
						echo '<a href="http://localhost:8888/mb2-portfolio/administrator/index.php?option=com_users&task=user.edit&id='.$item->created_by.'">'.$item->author_name.'</a>';
					}
					else{
						echo $item->author_name;
					}
					
					
					 ?>
                
				</td>
                
                
                
                
                
                
                <td class="hidden-phone center">
					<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
				</td>
                
                
                
                
                
                <td class="hidden-phone center">
					<?php echo $this->escape($item->access_level); ?>
				</td>
                
                
                
                
                
                <td class="hidden-phone center">
					<?php if ($item->language == '*'):?>
								<?php echo JText::alt('JALL', 'language'); ?>
							<?php else:?>
								<?php echo $item->language ? $this->escape($item->language) : JText::_('JUNDEFINED'); ?>
							<?php endif;?>
				</td>
                
                
                
                
                
                
                
                 <td class="hidden-phone center">
					<?php echo $this->escape($item->hits); ?>
				</td>
                
                
                


                <?php if (isset($this->items[0]->id)): ?>
					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
                <?php endif; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>        

		
