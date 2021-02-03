<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_rasp'))
{
	throw new InvalidArgumentException(Text::_('JERROR_ALERTNOAUTHOR'), 404);
}

// Require the helper
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/rasp.php';

JLoader::discover('YandexRasp', JPATH_LIBRARIES);
JLoader::register('YandexRasp', JPATH_LIBRARIES . '/YandexRasp.php');

// Execute the task
$controller = BaseController::getInstance('rasp');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
