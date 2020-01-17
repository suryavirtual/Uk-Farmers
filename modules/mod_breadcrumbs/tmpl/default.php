<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

$user = JFactory::getUser();
$jinput = JFactory::getApplication()->input;
$option = $jinput->get('option');

if($option == 'com_content'){
	$article = JTable::getInstance("content"); 
	$id = $jinput->getInt('id');
	$article->load($id); // Get Article 

	$article_images = $article->get("images"); // Get image parameters
	$pictures = json_decode($article_images); // Split the parameters apart
	$title1 = explode(" ",$article->title);
	
	if (!empty($images)) {
		$images = $pictures->image_intro;
	} else {
		$images = "images/Page-title-1.jpg";
	}
	
	if (!$user->guest) {
		$ftitle = "<h1>You <span>Are Logged In</span></h1>";
	} else {
		$ftitle = "<h1>".$title1['0']." <span>".$title1['1']."</span></h1>";
	}
} else if($option == 'com_mb2portfolio'){
	$ftitle = "<h1>Board <span>Members</span></h1>";
	$images = "images/Page-title-1.jpg";
} else if($option == 'com_contact'){
	$ftitle = "<h1>Contact</h1>";
	$images = "images/Page-title-1.jpg";
} else if($option == 'com_htmlmap2'){
	$ftitle = "<h1>Our <span>Members</span></h1>";
	$images = "images/Page-title-1.jpg";
} else {
	if (!$user->guest) {
		$ftitle = "<h1>You <span>Are Logged In</span></h1>";
	} else {
		$ftitle = "<h1>&nbsp;</h1>";
	}
	$images = "images/Page-title-1.jpg";
}
?>

<!--<style>
.page-title {background:#fff url("<?php echo JURI::base(); ?>/<?php echo $images; ?>") no-repeat scroll 50% 50% / cover ;}
</style>-->

<?php //echo "<h1> &nbsp;</h1>"; ?>
<ul itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb<?php echo $moduleclass_sfx; ?>">
	<?php if ($params->get('showHere', 1)) : ?>
		<li class="active">
			<?php echo JText::_('MOD_BREADCRUMBS_HERE'); ?>&#160;
		</li>
	<?php else : ?>
		<li class="active">
			<span class="divider icon-location"></span>
		</li>
	<?php endif; ?>

	<?php
	// Get rid of duplicated entries on trail including home page when using multilanguage
	for ($i = 0; $i < $count; $i++)
	{
		if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i - 1]->link) && $list[$i]->link == $list[$i - 1]->link)
		{
			unset($list[$i]);
		}
	}

	// Find last and penultimate items in breadcrumbs list
	end($list);
	$last_item_key = key($list);
	prev($list);
	$penult_item_key = key($list);

	// Make a link if not the last item in the breadcrumbs
	$show_last = $params->get('showLast', 1);

	// Generate the trail
	foreach ($list as $key => $item) :
		if ($key != $last_item_key) :
			// Render all but last item - along with separator ?>
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<?php if (!empty($item->link)) : ?>
					<a itemprop="item" href="<?php echo $item->link; ?>" class="pathway">
						<span itemprop="name">
							<?php //echo $item->name; ?>
						</span>
					</a>
				<?php else : ?>
					<span itemprop="name">
						<?php $item->name; ?>
					</span>
				<?php endif; ?>

				<?php if (($key != $penult_item_key) || $show_last) : ?>
					<span class="divider">
						<?php echo $separator; ?>
					</span>
				<?php endif; ?>
				<meta itemprop="position" content="<?php echo $key + 1; ?>">
			</li>
		<?php elseif ($show_last) :
			// Render last item if reqd. ?>
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="active">
				<span itemprop="name">
					<?php //echo $item->name; ?>
				</span>
				<meta itemprop="position" content="<?php echo $key + 1; ?>">
			</li>
		<?php endif;
	endforeach; ?>
</ul>
