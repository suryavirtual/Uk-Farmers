<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Supports a generic list of options.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldFalist extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Falist';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= $this->autofocus ? ' autofocus' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true' || (string) $this->disabled == '1'|| (string) $this->disabled == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		// Initialize JavaScript field attributes.
		$attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

		// Get the field options.
		$options = (array) $this->getOptions();

		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true')
		{
			$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
			$html[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '"/>';
		}
		else
		// Create a regular list.
		{
			$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}

		return implode($html);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array ( ''=>JText::_('JNONE'),'mb2portfolio-fa-glass' => 'glass', 'mb2portfolio-fa-music' => 'music', 'mb2portfolio-fa-search' => 'search', 'mb2portfolio-fa-envelope-o' => 'envelope-o', 'mb2portfolio-fa-heart' => 'heart', 'mb2portfolio-fa-star' => 'star', 'mb2portfolio-fa-star-o' => 'star-o', 'mb2portfolio-fa-user' => 'user', 'mb2portfolio-fa-film' => 'film', 'mb2portfolio-fa-th-large' => 'th-large', 'mb2portfolio-fa-th' => 'th', 'mb2portfolio-fa-th-list' => 'th-list', 'mb2portfolio-fa-check' => 'check', 'mb2portfolio-fa-times' => 'times', 'mb2portfolio-fa-search-plus' => 'search-plus', 'mb2portfolio-fa-search-minus' => 'search-minus', 'mb2portfolio-fa-power-off' => 'power-off', 'mb2portfolio-fa-signal' => 'signal', 'mb2portfolio-fa-cog' => 'cog', 'mb2portfolio-fa-trash-o' => 'trash-o', 'mb2portfolio-fa-home' => 'home', 'mb2portfolio-fa-file-o' => 'file-o', 'mb2portfolio-fa-clock-o' => 'clock-o', 'mb2portfolio-fa-road' => 'road', 'mb2portfolio-fa-download' => 'download', 'mb2portfolio-fa-arrow-circle-o-down' => 'arrow-circle-o-down', 'mb2portfolio-fa-arrow-circle-o-up' => 'arrow-circle-o-up', 'mb2portfolio-fa-inbox' => 'inbox', 'mb2portfolio-fa-play-circle-o' => 'play-circle-o', 'mb2portfolio-fa-repeat' => 'repeat', 'mb2portfolio-fa-refresh' => 'refresh', 'mb2portfolio-fa-list-alt' => 'list-alt', 'mb2portfolio-fa-lock' => 'lock', 'mb2portfolio-fa-flag' => 'flag', 'mb2portfolio-fa-headphones' => 'headphones', 'mb2portfolio-fa-volume-off' => 'volume-off', 'mb2portfolio-fa-volume-down' => 'volume-down', 'mb2portfolio-fa-volume-up' => 'volume-up', 'mb2portfolio-fa-qrcode' => 'qrcode', 'mb2portfolio-fa-barcode' => 'barcode', 'mb2portfolio-fa-tag' => 'tag', 'mb2portfolio-fa-tags' => 'tags', 'mb2portfolio-fa-book' => 'book', 'mb2portfolio-fa-bookmark' => 'bookmark', 'mb2portfolio-fa-print' => 'print', 'mb2portfolio-fa-camera' => 'camera', 'mb2portfolio-fa-font' => 'font', 'mb2portfolio-fa-bold' => 'bold', 'mb2portfolio-fa-italic' => 'italic', 'mb2portfolio-fa-text-height' => 'text-height', 'mb2portfolio-fa-text-width' => 'text-width', 'mb2portfolio-fa-align-left' => 'align-left', 'mb2portfolio-fa-align-center' => 'align-center', 'mb2portfolio-fa-align-right' => 'align-right', 'mb2portfolio-fa-align-justify' => 'align-justify', 'mb2portfolio-fa-list' => 'list', 'mb2portfolio-fa-outdent' => 'outdent', 'mb2portfolio-fa-indent' => 'indent', 'mb2portfolio-fa-video-camera' => 'video-camera', 'mb2portfolio-fa-picture-o' => 'picture-o', 'mb2portfolio-fa-pencil' => 'pencil', 'mb2portfolio-fa-map-marker' => 'map-marker', 'mb2portfolio-fa-adjust' => 'adjust', 'mb2portfolio-fa-tint' => 'tint', 'mb2portfolio-fa-pencil-square-o' => 'pencil-square-o', 'mb2portfolio-fa-share-square-o' => 'share-square-o', 'mb2portfolio-fa-check-square-o' => 'check-square-o', 'mb2portfolio-fa-arrows' => 'arrows', 'mb2portfolio-fa-step-backward' => 'step-backward', 'mb2portfolio-fa-fast-backward' => 'fast-backward', 'mb2portfolio-fa-backward' => 'backward', 'mb2portfolio-fa-play' => 'play', 'mb2portfolio-fa-pause' => 'pause', 'mb2portfolio-fa-stop' => 'stop', 'mb2portfolio-fa-forward' => 'forward', 'mb2portfolio-fa-fast-forward' => 'fast-forward', 'mb2portfolio-fa-step-forward' => 'step-forward', 'mb2portfolio-fa-eject' => 'eject', 'mb2portfolio-fa-chevron-left' => 'chevron-left', 'mb2portfolio-fa-chevron-right' => 'chevron-right', 'mb2portfolio-fa-plus-circle' => 'plus-circle', 'mb2portfolio-fa-minus-circle' => 'minus-circle', 'mb2portfolio-fa-times-circle' => 'times-circle', 'mb2portfolio-fa-check-circle' => 'check-circle', 'mb2portfolio-fa-question-circle' => 'question-circle', 'mb2portfolio-fa-info-circle' => 'info-circle', 'mb2portfolio-fa-crosshairs' => 'crosshairs', 'mb2portfolio-fa-times-circle-o' => 'times-circle-o', 'mb2portfolio-fa-check-circle-o' => 'check-circle-o', 'mb2portfolio-fa-ban' => 'ban', 'mb2portfolio-fa-arrow-left' => 'arrow-left', 'mb2portfolio-fa-arrow-right' => 'arrow-right', 'mb2portfolio-fa-arrow-up' => 'arrow-up', 'mb2portfolio-fa-arrow-down' => 'arrow-down', 'mb2portfolio-fa-share' => 'share', 'mb2portfolio-fa-expand' => 'expand', 'mb2portfolio-fa-compress' => 'compress', 'mb2portfolio-fa-plus' => 'plus', 'mb2portfolio-fa-minus' => 'minus', 'mb2portfolio-fa-asterisk' => 'asterisk', 'mb2portfolio-fa-exclamation-circle' => 'exclamation-circle', 'mb2portfolio-fa-gift' => 'gift', 'mb2portfolio-fa-leaf' => 'leaf', 'mb2portfolio-fa-fire' => 'fire', 'mb2portfolio-fa-eye' => 'eye', 'mb2portfolio-fa-eye-slash' => 'eye-slash', 'mb2portfolio-fa-exclamation-triangle' => 'exclamation-triangle', 'mb2portfolio-fa-plane' => 'plane', 'mb2portfolio-fa-calendar' => 'calendar', 'mb2portfolio-fa-random' => 'random', 'mb2portfolio-fa-comment' => 'comment', 'mb2portfolio-fa-magnet' => 'magnet', 'mb2portfolio-fa-chevron-up' => 'chevron-up', 'mb2portfolio-fa-chevron-down' => 'chevron-down', 'mb2portfolio-fa-retweet' => 'retweet', 'mb2portfolio-fa-shopping-cart' => 'shopping-cart', 'mb2portfolio-fa-folder' => 'folder', 'mb2portfolio-fa-folder-open' => 'folder-open', 'mb2portfolio-fa-arrows-v' => 'arrows-v', 'mb2portfolio-fa-arrows-h' => 'arrows-h', 'mb2portfolio-fa-bar-chart-o' => 'bar-chart-o', 'mb2portfolio-fa-twitter-square' => 'twitter-square', 'mb2portfolio-fa-facebook-square' => 'facebook-square', 'mb2portfolio-fa-camera-retro' => 'camera-retro', 'mb2portfolio-fa-key' => 'key', 'mb2portfolio-fa-cogs' => 'cogs', 'mb2portfolio-fa-comments' => 'comments', 'mb2portfolio-fa-thumbs-o-up' => 'thumbs-o-up', 'mb2portfolio-fa-thumbs-o-down' => 'thumbs-o-down', 'mb2portfolio-fa-star-half' => 'star-half', 'mb2portfolio-fa-heart-o' => 'heart-o', 'mb2portfolio-fa-sign-out' => 'sign-out', 'mb2portfolio-fa-linkedin-square' => 'linkedin-square', 'mb2portfolio-fa-thumb-tack' => 'thumb-tack', 'mb2portfolio-fa-external-link' => 'external-link', 'mb2portfolio-fa-sign-in' => 'sign-in', 'mb2portfolio-fa-trophy' => 'trophy', 'mb2portfolio-fa-github-square' => 'github-square', 'mb2portfolio-fa-upload' => 'upload', 'mb2portfolio-fa-lemon-o' => 'lemon-o', 'mb2portfolio-fa-phone' => 'phone', 'mb2portfolio-fa-square-o' => 'square-o', 'mb2portfolio-fa-bookmark-o' => 'bookmark-o', 'mb2portfolio-fa-phone-square' => 'phone-square', 'mb2portfolio-fa-twitter' => 'twitter', 'mb2portfolio-fa-facebook' => 'facebook', 'mb2portfolio-fa-github' => 'github', 'mb2portfolio-fa-unlock' => 'unlock', 'mb2portfolio-fa-credit-card' => 'credit-card', 'mb2portfolio-fa-rss' => 'rss', 'mb2portfolio-fa-hdd-o' => 'hdd-o', 'mb2portfolio-fa-bullhorn' => 'bullhorn', 'mb2portfolio-fa-bell' => 'bell', 'mb2portfolio-fa-certificate' => 'certificate', 'mb2portfolio-fa-hand-o-right' => 'hand-o-right', 'mb2portfolio-fa-hand-o-left' => 'hand-o-left', 'mb2portfolio-fa-hand-o-up' => 'hand-o-up', 'mb2portfolio-fa-hand-o-down' => 'hand-o-down', 'mb2portfolio-fa-arrow-circle-left' => 'arrow-circle-left', 'mb2portfolio-fa-arrow-circle-right' => 'arrow-circle-right', 'mb2portfolio-fa-arrow-circle-up' => 'arrow-circle-up', 'mb2portfolio-fa-arrow-circle-down' => 'arrow-circle-down', 'mb2portfolio-fa-globe' => 'globe', 'mb2portfolio-fa-wrench' => 'wrench', 'mb2portfolio-fa-tasks' => 'tasks', 'mb2portfolio-fa-filter' => 'filter', 'mb2portfolio-fa-briefcase' => 'briefcase', 'mb2portfolio-fa-arrows-alt' => 'arrows-alt', 'mb2portfolio-fa-users' => 'users', 'mb2portfolio-fa-link' => 'link', 'mb2portfolio-fa-cloud' => 'cloud', 'mb2portfolio-fa-flask' => 'flask', 'mb2portfolio-fa-scissors' => 'scissors', 'mb2portfolio-fa-files-o' => 'files-o', 'mb2portfolio-fa-paperclip' => 'paperclip', 'mb2portfolio-fa-floppy-o' => 'floppy-o', 'mb2portfolio-fa-square' => 'square', 'mb2portfolio-fa-bars' => 'bars', 'mb2portfolio-fa-list-ul' => 'list-ul', 'mb2portfolio-fa-list-ol' => 'list-ol', 'mb2portfolio-fa-strikethrough' => 'strikethrough', 'mb2portfolio-fa-underline' => 'underline', 'mb2portfolio-fa-table' => 'table', 'mb2portfolio-fa-magic' => 'magic', 'mb2portfolio-fa-truck' => 'truck', 'mb2portfolio-fa-pinterest' => 'pinterest', 'mb2portfolio-fa-pinterest-square' => 'pinterest-square', 'mb2portfolio-fa-google-plus-square' => 'google-plus-square', 'mb2portfolio-fa-google-plus' => 'google-plus', 'mb2portfolio-fa-money' => 'money', 'mb2portfolio-fa-caret-down' => 'caret-down', 'mb2portfolio-fa-caret-up' => 'caret-up', 'mb2portfolio-fa-caret-left' => 'caret-left', 'mb2portfolio-fa-caret-right' => 'caret-right', 'mb2portfolio-fa-columns' => 'columns', 'mb2portfolio-fa-sort' => 'sort', 'mb2portfolio-fa-sort-asc' => 'sort-asc', 'mb2portfolio-fa-sort-desc' => 'sort-desc', 'mb2portfolio-fa-envelope' => 'envelope', 'mb2portfolio-fa-linkedin' => 'linkedin', 'mb2portfolio-fa-undo' => 'undo', 'mb2portfolio-fa-gavel' => 'gavel', 'mb2portfolio-fa-tachometer' => 'tachometer', 'mb2portfolio-fa-comment-o' => 'comment-o', 'mb2portfolio-fa-comments-o' => 'comments-o', 'mb2portfolio-fa-bolt' => 'bolt', 'mb2portfolio-fa-sitemap' => 'sitemap', 'mb2portfolio-fa-umbrella' => 'umbrella', 'mb2portfolio-fa-clipboard' => 'clipboard', 'mb2portfolio-fa-lightbulb-o' => 'lightbulb-o', 'mb2portfolio-fa-exchange' => 'exchange', 'mb2portfolio-fa-cloud-download' => 'cloud-download', 'mb2portfolio-fa-cloud-upload' => 'cloud-upload', 'mb2portfolio-fa-user-md' => 'user-md', 'mb2portfolio-fa-stethoscope' => 'stethoscope', 'mb2portfolio-fa-suitcase' => 'suitcase', 'mb2portfolio-fa-bell-o' => 'bell-o', 'mb2portfolio-fa-coffee' => 'coffee', 'mb2portfolio-fa-cutlery' => 'cutlery', 'mb2portfolio-fa-file-text-o' => 'file-text-o', 'mb2portfolio-fa-building-o' => 'building-o', 'mb2portfolio-fa-hospital-o' => 'hospital-o', 'mb2portfolio-fa-ambulance' => 'ambulance', 'mb2portfolio-fa-medkit' => 'medkit', 'mb2portfolio-fa-fighter-jet' => 'fighter-jet', 'mb2portfolio-fa-beer' => 'beer', 'mb2portfolio-fa-h-square' => 'h-square', 'mb2portfolio-fa-plus-square' => 'plus-square', 'mb2portfolio-fa-angle-double-left' => 'angle-double-left', 'mb2portfolio-fa-angle-double-right' => 'angle-double-right', 'mb2portfolio-fa-angle-double-up' => 'angle-double-up', 'mb2portfolio-fa-angle-double-down' => 'angle-double-down', 'mb2portfolio-fa-angle-left' => 'angle-left', 'mb2portfolio-fa-angle-right' => 'angle-right', 'mb2portfolio-fa-angle-up' => 'angle-up', 'mb2portfolio-fa-angle-down' => 'angle-down', 'mb2portfolio-fa-desktop' => 'desktop', 'mb2portfolio-fa-laptop' => 'laptop', 'mb2portfolio-fa-tablet' => 'tablet', 'mb2portfolio-fa-mobile' => 'mobile', 'mb2portfolio-fa-circle-o' => 'circle-o', 'mb2portfolio-fa-quote-left' => 'quote-left', 'mb2portfolio-fa-quote-right' => 'quote-right', 'mb2portfolio-fa-spinner' => 'spinner', 'mb2portfolio-fa-circle' => 'circle', 'mb2portfolio-fa-reply' => 'reply', 'mb2portfolio-fa-github-alt' => 'github-alt', 'mb2portfolio-fa-folder-o' => 'folder-o', 'mb2portfolio-fa-folder-open-o' => 'folder-open-o', 'mb2portfolio-fa-smile-o' => 'smile-o', 'mb2portfolio-fa-frown-o' => 'frown-o', 'mb2portfolio-fa-meh-o' => 'meh-o', 'mb2portfolio-fa-gamepad' => 'gamepad', 'mb2portfolio-fa-keyboard-o' => 'keyboard-o', 'mb2portfolio-fa-flag-o' => 'flag-o', 'mb2portfolio-fa-flag-checkered' => 'flag-checkered', 'mb2portfolio-fa-terminal' => 'terminal', 'mb2portfolio-fa-code' => 'code', 'mb2portfolio-fa-reply-all' => 'reply-all', 'mb2portfolio-fa-mail-reply-all' => 'mail-reply-all', 'mb2portfolio-fa-star-half-o' => 'star-half-o', 'mb2portfolio-fa-location-arrow' => 'location-arrow', 'mb2portfolio-fa-crop' => 'crop', 'mb2portfolio-fa-code-fork' => 'code-fork', 'mb2portfolio-fa-chain-broken' => 'chain-broken', 'mb2portfolio-fa-question' => 'question', 'mb2portfolio-fa-info' => 'info', 'mb2portfolio-fa-exclamation' => 'exclamation', 'mb2portfolio-fa-superscript' => 'superscript', 'mb2portfolio-fa-subscript' => 'subscript', 'mb2portfolio-fa-eraser' => 'eraser', 'mb2portfolio-fa-puzzle-piece' => 'puzzle-piece', 'mb2portfolio-fa-microphone' => 'microphone', 'mb2portfolio-fa-microphone-slash' => 'microphone-slash', 'mb2portfolio-fa-shield' => 'shield', 'mb2portfolio-fa-calendar-o' => 'calendar-o', 'mb2portfolio-fa-fire-extinguisher' => 'fire-extinguisher', 'mb2portfolio-fa-rocket' => 'rocket', 'mb2portfolio-fa-maxcdn' => 'maxcdn', 'mb2portfolio-fa-chevron-circle-left' => 'chevron-circle-left', 'mb2portfolio-fa-chevron-circle-right' => 'chevron-circle-right', 'mb2portfolio-fa-chevron-circle-up' => 'chevron-circle-up', 'mb2portfolio-fa-chevron-circle-down' => 'chevron-circle-down', 'mb2portfolio-fa-html5' => 'html5', 'mb2portfolio-fa-css3' => 'css3', 'mb2portfolio-fa-anchor' => 'anchor', 'mb2portfolio-fa-unlock-alt' => 'unlock-alt', 'mb2portfolio-fa-bullseye' => 'bullseye', 'mb2portfolio-fa-ellipsis-h' => 'ellipsis-h', 'mb2portfolio-fa-ellipsis-v' => 'ellipsis-v', 'mb2portfolio-fa-rss-square' => 'rss-square', 'mb2portfolio-fa-play-circle' => 'play-circle', 'mb2portfolio-fa-ticket' => 'ticket', 'mb2portfolio-fa-minus-square' => 'minus-square', 'mb2portfolio-fa-minus-square-o' => 'minus-square-o', 'mb2portfolio-fa-level-up' => 'level-up', 'mb2portfolio-fa-level-down' => 'level-down', 'mb2portfolio-fa-check-square' => 'check-square', 'mb2portfolio-fa-pencil-square' => 'pencil-square', 'mb2portfolio-fa-external-link-square' => 'external-link-square', 'mb2portfolio-fa-share-square' => 'share-square', 'mb2portfolio-fa-compass' => 'compass', 'mb2portfolio-fa-caret-square-o-down' => 'caret-square-o-down', 'mb2portfolio-fa-caret-square-o-up' => 'caret-square-o-up', 'mb2portfolio-fa-caret-square-o-right' => 'caret-square-o-right', 'mb2portfolio-fa-eur' => 'eur', 'mb2portfolio-fa-gbp' => 'gbp', 'mb2portfolio-fa-usd' => 'usd', 'mb2portfolio-fa-inr' => 'inr', 'mb2portfolio-fa-jpy' => 'jpy', 'mb2portfolio-fa-rub' => 'rub', 'mb2portfolio-fa-krw' => 'krw', 'mb2portfolio-fa-btc' => 'btc', 'mb2portfolio-fa-file' => 'file', 'mb2portfolio-fa-file-text' => 'file-text', 'mb2portfolio-fa-sort-alpha-asc' => 'sort-alpha-asc', 'mb2portfolio-fa-sort-alpha-desc' => 'sort-alpha-desc', 'mb2portfolio-fa-sort-amount-asc' => 'sort-amount-asc', 'mb2portfolio-fa-sort-amount-desc' => 'sort-amount-desc', 'mb2portfolio-fa-sort-numeric-asc' => 'sort-numeric-asc', 'mb2portfolio-fa-sort-numeric-desc' => 'sort-numeric-desc', 'mb2portfolio-fa-thumbs-up' => 'thumbs-up', 'mb2portfolio-fa-thumbs-down' => 'thumbs-down', 'mb2portfolio-fa-youtube-square' => 'youtube-square', 'mb2portfolio-fa-youtube' => 'youtube', 'mb2portfolio-fa-xing' => 'xing', 'mb2portfolio-fa-xing-square' => 'xing-square', 'mb2portfolio-fa-youtube-play' => 'youtube-play', 'mb2portfolio-fa-dropbox' => 'dropbox', 'mb2portfolio-fa-stack-overflow' => 'stack-overflow', 'mb2portfolio-fa-instagram' => 'instagram', 'mb2portfolio-fa-flickr' => 'flickr', 'mb2portfolio-fa-adn' => 'adn', 'mb2portfolio-fa-bitbucket' => 'bitbucket', 'mb2portfolio-fa-bitbucket-square' => 'bitbucket-square', 'mb2portfolio-fa-tumblr' => 'tumblr', 'mb2portfolio-fa-tumblr-square' => 'tumblr-square', 'mb2portfolio-fa-long-arrow-down' => 'long-arrow-down', 'mb2portfolio-fa-long-arrow-up' => 'long-arrow-up', 'mb2portfolio-fa-long-arrow-left' => 'long-arrow-left', 'mb2portfolio-fa-long-arrow-right' => 'long-arrow-right', 'mb2portfolio-fa-apple' => 'apple', 'mb2portfolio-fa-windows' => 'windows', 'mb2portfolio-fa-android' => 'android', 'mb2portfolio-fa-linux' => 'linux', 'mb2portfolio-fa-dribbble' => 'dribbble', 'mb2portfolio-fa-skype' => 'skype', 'mb2portfolio-fa-foursquare' => 'foursquare', 'mb2portfolio-fa-trello' => 'trello', 'mb2portfolio-fa-female' => 'female', 'mb2portfolio-fa-male' => 'male', 'mb2portfolio-fa-gittip' => 'gittip', 'mb2portfolio-fa-sun-o' => 'sun-o', 'mb2portfolio-fa-moon-o' => 'moon-o', 'mb2portfolio-fa-archive' => 'archive', 'mb2portfolio-fa-bug' => 'bug', 'mb2portfolio-fa-vk' => 'vk', 'mb2portfolio-fa-weibo' => 'weibo', 'mb2portfolio-fa-renren' => 'renren', 'mb2portfolio-fa-pagelines' => 'pagelines', 'mb2portfolio-fa-stack-exchange' => 'stack-exchange', 'mb2portfolio-fa-arrow-circle-o-right' => 'arrow-circle-o-right', 'mb2portfolio-fa-arrow-circle-o-left' => 'arrow-circle-o-left', 'mb2portfolio-fa-caret-square-o-left' => 'caret-square-o-left', 'mb2portfolio-fa-dot-circle-o' => 'dot-circle-o', 'mb2portfolio-fa-wheelchair' => 'wheelchair', 'mb2portfolio-fa-vimeo-square' => 'vimeo-square', 'mb2portfolio-fa-try' => 'try', 'mb2portfolio-fa-plus-square-o' => 'plus-square-o' );

		foreach ($this->element->children() as $option)
		{
			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			// Filter requirements
			if ($requires = explode(',', (string) $option['requires']))
			{
				// Requires multilanguage
				if (in_array('multilanguage', $requires) && !JLanguageMultilang::isEnabled())
				{
					continue;
				}

				// Requires associations
				if (in_array('associations', $requires) && !JLanguageAssociations::isEnabled())
				{
					continue;
				}
			}

			$value = (string) $option['value'];

			$disabled = (string) $option['disabled'];
			$disabled = ($disabled == 'true' || $disabled == 'disabled' || $disabled == '1');

			$disabled = $disabled || ($this->readonly && $value != $this->value);

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
				'select.option', $value,
				JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text',
				$disabled
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
}
