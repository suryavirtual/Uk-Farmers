<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */
?>
<dl class="contact-address dl-horizontal" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
	<?php if (($this->params->get('address_check') > 0) &&
		($this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode)) : ?>
		
		<?php if ($this->contact->suburb && $this->params->get('show_suburb')) : ?>
			<!--<dd>
				<span class="contact-suburb" itemprop="addressLocality">
					<?php //echo "Office: ".$this->contact->suburb . '<br />'; ?>
				</span>
			</dd>-->
		<?php endif; ?>
		
		<?php //if ($this->params->get('address_check') > 0) : ?>
			<!--<dt>
				<span class="<?php //echo $this->params->get('marker_class'); ?>" >
					<?php //echo "Address: "; ?>
				</span>
			</dt>-->
		<?php //endif; ?>

		<?php if ($this->contact->address && $this->params->get('show_street_address')) : ?>
			<dd><b style="font-size:15px;"><?php echo "Address: "; ?></b><br>
				<span class="contact-street" itemprop="streetAddress">
					<?php $add = explode(",", $this->contact->address);
					echo $add['0'].",<br>".$add['1'].",<br>".$add['2'].",<br>";
					$subrub = explode(",", $this->contact->suburb);
					echo $subrub['0'].",<br>".$subrub['1'];
						//echo "<pre>"; print_r($subrub); echo "</pre>";?>
				</span>
			</dd>
		<?php endif; ?>
		
		<?php if ($this->contact->telephone && $this->params->get('show_telephone')) : ?>
	<!--<dt>
		<span class="<?php //echo $this->params->get('marker_class'); ?>" >
			<?php //echo "Phone: "; ?>
		</span>
	</dt>-->
	<dd><br><b style="font-size:15px;"><?php echo "Telephone: "; ?></b><br>
		<span class="contact-telephone" itemprop="telephone">
			<?php echo nl2br($this->contact->telephone); ?>
		</span>
	</dd>
<?php endif; ?>


<?php if ($this->contact->fax && $this->params->get('show_fax')) : ?>
	<!--<dt>
		<span class="<?php //echo $this->params->get('marker_class'); ?>">
			<?php //echo "Fax: "; ?>
		</span>
	</dt>-->
	<dd><b style="font-size:15px;"><?php echo "Fax: "; ?></b><br>
		<span class="contact-fax" itemprop="faxNumber">
		<?php echo nl2br($this->contact->fax); ?>
		</span>
	</dd>
<?php endif; ?>

		
		<?php //if ($this->contact->email_to && $this->params->get('show_email')) : ?>
		<!--<dt>
			<span class="<?php //echo $this->params->get('marker_class'); ?>" itemprop="email">
				<?php //echo "Email: "; ?>
			</span>
		</dt>-->
		<dd><b style="font-size:15px;"><?php echo "Email: "; ?></b><br>
			<span class="contact-emailto">
				<?php //echo $this->contact->email_to; ?>
				<a href="mailto:<?php echo $this->contact->email_to; ?>"><?php echo $this->contact->email_to; ?></a>
			</span>
		</dd>
<?php //endif; ?>


<?php if ($this->contact->webpage && $this->params->get('show_webpage')) : ?>
	<!--<dt>
		<span class="<?php //echo $this->params->get('marker_class'); ?>" >
			<?php// echo "Website: "; ?>
		</span>
	</dt>-->
	<dd><b style="font-size:15px;"><?php echo "Website: "; ?></b><br>
		<span class="contact-webpage">
			<a href="<?php echo $this->contact->webpage; ?>" target="_blank" itemprop="url">
			<?php echo JStringPunycode::urlToUTF8($this->contact->webpage); ?></a>
		</span>
	</dd>
<?php endif; ?>

		<?php if ($this->contact->postcode && $this->params->get('show_postcode')) : ?>
			<dt>
				<span class="po-code" itemprop="postalCode">
					<?php echo "Post Code: "; ?>
				</span>
			</dt>
			<dd>
				<span class="contact-postcode" itemprop="postalCode">
					<?php echo $this->contact->postcode . '<br />'; ?>
				</span>
			</dd>
		<?php endif; ?>

		<?php if ($this->contact->country && $this->params->get('show_country')) : ?>
		<dt>
			<span class="po-code" itemprop="addressCountry">
				<?php echo "Country: "; ?>
			</span>
		</dt>
		<dd>
			<span class="contact-country" itemprop="addressCountry">
				<?php echo $this->contact->country . '<br />'; ?>
			</span>
		</dd>
		<?php endif; ?>
		
		<?php if ($this->contact->state && $this->params->get('show_state')) : ?>
			<dd>
				<span class="contact-state" itemprop="addressRegion">
					<?php echo $this->contact->state . '<br />'; ?>
				</span>
			</dd>
		<?php endif; ?>
		
		
	<?php endif; ?>





<?php if ($this->contact->mobile && $this->params->get('show_mobile')) :?>
	<dt>
		<span class="<?php echo $this->params->get('marker_class'); ?>" >
			<?php echo "Mobile: "; ?>
		</span>
	</dt>
	<dd>
		<span class="contact-mobile" itemprop="telephone">
			<?php echo nl2br($this->contact->mobile); ?>
		</span>
	</dd>
<?php endif; ?>

</dl>
