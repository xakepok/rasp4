<?php
defined('_JEXEC') or die;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\MVC\Controller\BaseController;

class RaspControllerStops extends BaseController
{
    public function getModel($name = 'Stops', $prefix = 'RaspModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function execute($task)
    {
        $items = $this->getModel()->getItems();
        echo new JsonResponse($items);
    }
}