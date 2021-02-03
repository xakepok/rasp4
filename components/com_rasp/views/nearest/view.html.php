<?php
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class RaspViewNearest extends HtmlView
{
    protected $items;
    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        return parent::display($tpl);
    }
}
