<?php
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;

class TableRaspPoints extends Table
{
    var $id = null;
    var $station_1 = null;
    var $station_2 = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__rasp_points', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}
