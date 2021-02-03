<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class RaspControllerCron extends BaseController
{
    public function __construct($config = array())
    {
        $this->prefix = "RaspModel";
        if (!$this->auth()) die("Bad username or password in component's settings");
        parent::__construct($config);
    }

    public function loadThreads()
    {
        JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . "/tables");
        JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . "/models", $this->prefix);
        $model = JModelLegacy::getInstance("Schedule", $this->prefix, ['cron' => true]);

        $result = $model->getThreads();

        exit($result);
    }

    public function loadStops()
    {
        JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . "/tables");
        JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . "/models", $this->prefix);
        $model = JModelLegacy::getInstance("Schedule", $this->prefix, ['cron' => true]);

        $result = $model->getStops();

        exit($result);
    }

    private function auth(): bool
    {
        $username = RaspHelper::getConfig('cron_username', null);
        $password = RaspHelper::getConfig('cron_password', null);
        if ($username === null || $password === null) die("Not set username and password in component's settings");
        jimport('joomla.user.helper');
        return JFactory::getApplication('administrator')->login(['username' => $username, 'password' => $password], ['remember' => true]);
    }

    private $prefix;
}
