<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_smadmin
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
require_once JPATH_COMPONENT .'/helpers/html/product.php';
JHtml::_('formbehavior.chosen', 'select');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$saveOrderingUrl = 'index.php?option=com_smadmin&task=products.saveorder';
JHtml::_('sortablelist.sortable', 'productList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);

//echo "<pre>"; print_r($this); echo "</pre>";
?>
<form action="index.php?option=com_smadmin&view=products" method="post" id="adminForm" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('COM_SMAMDIN_SMADMINS_FILTER'); ?>
			<?php
				echo JLayoutHelper::render(
					'joomla.searchtools.default',
					array('view' => $this)
				);
			?>
		</div>
	</div>
	<table class="table table-striped table-hover" id="productList">
		<thead>
		<tr>
			<th width="1%" class="nowrap center hidden-phone">
				<?php echo JHtml::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
			</th>
			<th width="2%">
				<?php echo JHtml::_('grid.checkall'); ?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_PRODUCT_NAME', 'pName', $listDirn, $listOrder); ?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_PRODUCT_SKU', 'pSKU', $listDirn, $listOrder); ?>
			</th>
			<th width="30%" style="color:#0088cc">Product Image</th>
			<th width="20%" style="color:#0088cc">Product Doc</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_PRODUCT_COMPANY_NAME', 'pName', $listDirn, $listOrder); ?>
			</th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_PUBLISHED', 'published', $listDirn, $listOrder); ?>
			</th>
			<th width="2%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_ID', 'id', $listDirn, $listOrder); ?>
			</th>
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php foreach ($this->items as $i => $row) :
					$orderkey = $row->ordering;
					//$link = JRoute::_('index.php?option=com_smadmin&view=product&task=product.edit&id=' . $row->pId);
					$link = JRoute::_('index.php?option=com_smadmin&view=product&layout=edit&pId=' . $row->pId);
				?>
					<tr>
						<td class="order nowrap center hidden-phone">
						   <?php
						   $iconClass = '';
						   $canChange = true;
						   $saveOrder = true;
						   if (!$canChange)
						   {
							$iconClass = ' inactive';
						   }
						   elseif (!$saveOrder)
						   {
							$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
						   }
						   ?>
						   <span class="sortable-handler<?php echo $iconClass ?>">
							<span class="icon-menu"></span>
						   </span>
						   <?php if ($canChange && $saveOrder) : ?>
							<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey; ?>" />
						   <?php endif; ?>
						  </td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->pId); ?>
						</td>
						<td>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SMADMIN_EDIT_PRODUCT'); ?>">
								<?php echo $row->pName; ?>
							</a>
						</td>
						<td>
							<?php echo $row->pSKU; ?>
						</td>
						<td>
							<?php 
							$pImage = explode(",",$row->pImage);
							if(!empty($pImage['0'])){
								foreach($pImage as $pImages){ 
									echo '<span style="margin:4px;"><img src="'.JURI::root().'/media/com_mtree/images/products/'.$pImages.'" width="80" height="80" /></span>';
								}
							} else {
								echo "No Image!!!";
							}
							?>
						</td>
						<?php if(!empty($row->pDocs)){ ?>
						<td><a href="<?php echo JURI::root() ?>/media/com_mtree/images/products/docs/<?php echo $row->pDocs; ?>"><?php echo "Document"; ?></a></td>
						<?php } else { ?>
							<td><?php echo "No Document"; ?></td>
						<?php }?>
						<td>
							<?php echo $row->link_name; ?>
						</td>
						<td align="center">
							<?php echo JHtml::_('Product.approved', $row->status, $i, 'products.'); ?>
						</td>
						<td align="center">
							<?php echo $row->pId; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

