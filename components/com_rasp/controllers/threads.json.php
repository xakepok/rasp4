<?php
defined('_JEXEC') or die;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\MVC\Controller\BaseController;

class RaspControllerThreads extends BaseController
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->search = $this->input->getString('search');
        if (is_null($this->search) || strlen($this->search) < 4) {
            echo new JsonResponse([], 'Empty query', true);
            jexit();
        }
    }

    public function getModel($name = 'Threads', $prefix = 'RaspModel', $config = [])
    {
        return parent::getModel($name, $prefix, ['search' => $this->search]);
    }

    public function execute($task)
    {
        $items = $this->getModel()->getItems();
        echo new JsonResponse($items);
    }

    private $search;
}