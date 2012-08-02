<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_templates/helpers/templates.php';

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$clientId       = $this->item->client_id;
$state          = $this->state->get('filter.state');
$templates      = array_keys(ModulesHelper::getTemplates($clientId, $state));
$templateGroups = array();

// Add an empty value to be able to deselect a module position
$option = createOption();
$templateGroups[''] = createOptionGroup('', array($option));

// Add positions from templates
$isTemplatePosition = false;
foreach ($templates as $template)
{
	$options = array();

	$positions = TemplatesHelper::getPositions($clientId, $template);
	foreach ($positions as $position)
	{
		$text = getTranslatedModulePosition($template, $position) . ' [' . $position . ']';
		$options[] = createOption($position, $text);

		if (!$isTemplatePosition && $this->item->position === $position)
		{
			$isTemplatePosition = true;
		}
	}

	$templateGroups[$template] = createOptionGroup(ucfirst($template), $options);
}

// Add custom position to options
$customGroupText = JText::_('COM_MODULES_CUSTOM_POSITION');
if (!empty($this->item->position) && !$isTemplatePosition)
{
	$option = createOption($this->item->position);
	$templateGroups[$customGroupText] = createOptionGroup($customGroupText, array($option));
}

// Build field
$attr = array(
	'id'          => 'jform_position',
	'list.select' => $this->item->position,
	'list.attr'   => 'class="chzn-custom-value input-xlarge" '
		. 'data-custom_group_text="' . $customGroupText . '" '
		. 'data-no_results_text="' . JText::_('COM_MODULES_ADD_CUSTOM_POSITION') . '" '
		. 'data-placeholder="' . JText::_('COM_MODULES_TYPE_OR_SELECT_POSITION') . '" '
);

echo JHtml::_('select.groupedlist', $templateGroups, 'jform[position]', $attr);

/**
 * Return a translated module position name
 *
 * @param   string  $template  Template name
 * @param   string  $position  Position name
 *
 * @return  string             Return a translated position name
 */
function getTranslatedModulePosition($template, $position)
{
	// Template translation
	$lang = JFactory::getLanguage();
	$lang->load('tpl_' . $template . '.sys', JPATH_SITE . '/templates/' . $template, $lang->getDefault(), false, false);

	$langKey = strtoupper('TPL_' . $template . '_POSITION_' . $position);
	$text = JText::_($langKey);

	// Avoid untranslated strings
	if (!isTranslatedText($langKey, $text))
	{
		// Modules component translation
		$langKey = strtoupper('COM_MODULES_POSITION_' . $position);
		$text = JText::_($langKey);

		// Avoid untranslated strings
		if (!isTranslatedText($langKey, $text))
		{
			// Try to humanize the position name
			$text = ucfirst(preg_replace('/^' . $template . '\-/', '', $position));
			$text = ucwords(str_replace(array('-', '_'), ' ', $text));
		}
	}

	return $text;
}

/**
 * Check if the string was translated
 *
 * @param   string  $langKey  Language file text key
 * @param   string  $text     The "translated" text to be checked
 *
 * @return  boolean           Return true for translated text
 */
function isTranslatedText($langKey, $text)
{
	return $text !== $langKey;
}

/**
 * Create and return a new Option
 *
 * @param   string  $value  The option value [optional]
 * @param   string  $text   The option text [optional]
 *
 * @return  object          The option as an object (stdClass instance)
 */
function createOption($value = '', $text = '')
{
	if (empty($text))
	{
		$text = $value;
	}

	$option = new stdClass;
	$option->value = $value;
	$option->text  = $text;

	return $option;
}

/**
 * Create and return a new Option Group
 *
 * @param   string  $label    Value and label for group [optional]
 * @param   array   $options  Array of options to insert into group [optional]
 *
 * @return  array             Return the new group as an array
 */
function createOptionGroup($label = '', $options = array())
{
	$group = array();
	$group['value'] = $label;
	$group['text']  = $label;
	$group['items'] = $options;

	return $group;
}
