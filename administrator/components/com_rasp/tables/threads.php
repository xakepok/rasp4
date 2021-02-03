<?php
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;

class TableRaspThreads extends Table
{
    var $id = null;
    var $dat = null;
    var $uid = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__rasp_threads', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}
