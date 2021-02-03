<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/rasp.php';
JLoader::discover('YandexRasp', JPATH_LIBRARIES);
JLoader::register('YandexRasp', JPATH_LIBRARIES . '/YandexRasp.php');


$controller = BaseController::getInstance('rasp');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
