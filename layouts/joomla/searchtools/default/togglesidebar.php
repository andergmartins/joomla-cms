<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

// Set the tooltips
JText::script('JSEARCH_HIDE_SIDEBAR');
JText::script('JSEARCH_SHOW_SIDEBAR');
?>
<div
	id="j-toggle-sidebar-button"
	class="hidden-phone hasTooltip"
	title="<?php echo JHtml::tooltipText('JSEARCH_HIDE_SIDEBAR'); ?>"
	type="button"
	onclick="Joomla.toggleSidebar(false); return false;"
	>
	<span id="j-toggle-sidebar-icon" class="icon-remove"></span>
</div>
