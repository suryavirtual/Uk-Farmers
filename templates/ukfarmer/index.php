<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.ukfarmer
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$user            = JFactory::getUser();
$this->language  = $doc->language;
$this->direction = $doc->direction;

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');

if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/template.js');

// Add Stylesheets
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css');

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Adjusting content width
if ($this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span6";
}
elseif ($this->countModules('position-7') && !$this->countModules('position-8'))
{
	$span = "span9";
}
elseif (!$this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span9";
}
else
{
	$span = "span12";
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . JUri::root() . $this->params->get('logoFile') . '" alt="' . $sitename . '" width="263" height="62" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle')) . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}

$menu = JFactory::getApplication()->getMenu();
if ($menu->getActive() == $menu->getDefault()) {
	$class = '';
} else {
	$class = 'page-title';
}
?>
<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
	<?php // Use of Google Font ?>
	<?php if ($this->params->get('googleFont')) : ?>
		
	<!--<link href='http://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css' rel='stylesheet' type='text/css' />-->

		<style type="text/css">
			h1,h2,h3,h4,h5,h6,.site-title{
				font-family: '<?php echo str_replace('+', ' ', $this->params->get('googleFontName')); ?>', sans-serif;
			}
		</style>
	<?php endif; ?>
	<?php // Template color ?>
	
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
	echo ($this->direction == 'rtl' ? ' rtl' : '');
?>">

<!-- top enquiry & call us -->
<section class="top-bar" id="awd-top-bar-wrapper">
	<div class="containerTop">
		<div id="top-bar" class="row">
			<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 " id="tz-top-bar-1">
				<jdoc:include type="modules" name="top-bar-1" style="none" />
			</div>
			<?php  $user = JFactory::getUser();
				   $comp_ids=explode(',',$user->comp_list);
                   $total_comp = count($comp_ids);
		           if($total_comp == "1"):?>
			<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12  right-bar right pull-right log_in" id="tz-top-bar-2">
				<jdoc:include type="modules" name="top-bar-2" style="none" />
			</div>
		<?php else : ?>
			<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12  right-bar right pull-right log_in" id="tz-top-bar-2">
				<jdoc:include type="modules" name="top-bar-3" style="none" />
			</div>

		<?php endif; ?>
		</div>
	</div>
</section>
<!-- end top enquiry & call us -->

<!-- start Header -->
<header class="top-head">
	<div class="containerTop">
		<div class="row" id="header">
			<!-- site Logo -->
			<div class="logo">
			<a class="brand pull-left" href="<?php echo $this->baseurl; ?>/">
				<?php echo $logo; ?>
				<?php if ($this->params->get('sitedescription')) : ?>
					<?php echo '<div class="site-description">' . htmlspecialchars($this->params->get('sitedescription')) . '</div>'; ?>
				<?php endif; ?>
			</a>
			</div>
			<!-- end site Logo -->
			
			<!-- start Navigation -->
			<?php if ($this->countModules('position-1')) : ?>
			<nav class="navigation" role="navigation">
				<div class="navbar pull-left">
					<a class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
				</div>
				<?php  $user = JFactory::getUser();
				 if($user->guest!="0"):?>
				
				<div class="nav-collapse top-nav">
					<jdoc:include type="modules" name="position-1" style="none" />
				</div>
				<?php endif;?>
				<div class="nav-collapse top-nav">
					<jdoc:include type="modules" name="position-4" style="none" />
				</div>
			</nav>
			<?php endif; ?>
			<!-- end navigation -->
			<div class="header-search pull-right" style="display:none;">
				<jdoc:include type="modules" name="position-0" style="none" />
			</div>
		</div>
	</div>
</header>
<!-- end Header -->

<!-- Banner Area -->
<section id="banner" class="home_banners">
	
	<div class="<?php echo $class; ?>leftbanners">
		<jdoc:include type="modules" name="banner" style="xhtml" />
	</div>
	
	
	<div class="containerTop">
		<div class="row-fluid">
			<?php if ($this->countModules('position-8')) : ?>
				<!-- Begin Left Sidebar -->
				<div id="sidebar" class="span3">
					<div class="sidebar-nav">
						<jdoc:include type="modules" name="position-8" style="xhtml" />
					</div>
				</div>
				<!-- End Left Sidebar -->
			<?php endif; ?>
			
			<!-- start middle Main area -->
			<main id="content" role="main" class="<?php echo $span; ?>">
				<!-- Begin Content -->
				<jdoc:include type="modules" name="position-3" style="xhtml" />
				<jdoc:include type="message" />
				<jdoc:include type="component" />
				<div class="tab-section">
					<div class="tab-right">
						<jdoc:include type="modules" name="Dinamod" style="xhtml" />
					</div>
					<div class="tab-left">
						<jdoc:include type="modules" name="position-9" style="xhtml" />
					</div>
				</div>
				<!-- End Content -->
			</main>
			<!-- end middle Main area -->
			
			<!-- Right Side Bar -->
			<?php if ($this->countModules('position-7')) : ?>
				<div id="aside" class="span3">
					<!-- Begin Right Sidebar -->
					<jdoc:include type="modules" name="position-7" style="well" />
					<!-- End Right Sidebar -->
				</div>
			<?php endif; ?>
			<!-- end Right Side Bar -->	
		</div>
	</div>
</section>
<?php if ($this->countModules('position-11')) : ?>
<section class="our-plan">
	<div class="containerTop">
		<jdoc:include type="modules" name="position-11" style="none" />
	</div>
</section>
<?php endif; ?>
<!-- end Banner Area -->

<!-- section for 4main block -->
<section class="gry-bg">
	<div class="containerTop" class="gry-bg">
		<!-- start 4 Blocks -->
		<jdoc:include type="modules" name="position-2" style="none" />
		<!-- end 4 Blocks -->
		
		<!-- start Logo Scroller -->
		<jdoc:include type="modules" name="position-10" style="none" />
		<!-- end Logo Scroller -->
	</div>
</section>
<!-- end section for 4 main block -->

<!-- start footer -->
<section id="awd-footer-top-wrapper" class="footer-top">
	<div class="containerTop">
		<div class="footer-left">
			<div class="col-lg-6" id="footer-top">
				<jdoc:include type="modules" name="footer-1" style="xhtml" />
				<jdoc:include type="modules" name="footer-2" style="xhtml" />
				<jdoc:include type="modules" name="footer-3" style="xhtml" />
			</div>
		</div>
		<div class="footer-right">
			<div class="col-lg-6 col-md-3 col-sm-12 col-xs-12" id="footer-top-1">
				<jdoc:include type="modules" name="footer-4" style="xhtml" />
			</div>
		</div>
	</div>
</section>
<!-- end start footer -->

<!-- footer -->
<section class="footer-bottom">
	<div class="containerTop">
		<div class="col-lg-5 copyrights">
			<jdoc:include type="modules" name="footer" style="none" />
		</div>
		<div class="col-lg-7">
			<jdoc:include type="modules" name="footer-social" style="none" />
		</div>
	</div>
</section>
</body>
</html>
