<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class RaspControllerSchedule extends BaseController
{
    public function getThreads()
    {
        $model = $this->getModel();
        $rasp = $model->getThreads();
        exit(var_dump($rasp));
    }

    public function getStops()
    {
        $model = $this->getModel();
        $rasp = $model->getStops();
        exit(var_dump($rasp));
    }

    public function getModel($name = 'Schedule', $prefix = 'RaspModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }
}
