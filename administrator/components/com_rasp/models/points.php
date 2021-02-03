<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class RaspModelPoints extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'p.id',
            );
        }
        parent::__construct($config);
        $input = JFactory::getApplication()->input;
        $this->dat = $config['date'] ?? JDate::getInstance()->format("Y-m-d");
        $this->export = ($input->getString('format', 'html') === 'html') ? false : true;
    }

    protected function _getListQuery()
    {
        $query = $this->_db->getQuery(true);

        /* Сортировка */
        $orderCol = "p.id";
        $orderDirn = "ASC";

        //Ограничение длины списка
        $limit = (int) RaspHelper::getConfig('count_points', 10);

        $query
            ->select("p.id, s1.yandex as station_1, s2.yandex as station_2")
            ->from("#__rasp_points p")
            ->leftJoin("#__rw_stations s1 on s1.id = p.station_1")
            ->leftJoin("#__rw_stations s2 on s2.id = p.station_2");

        if (!empty($this->dat)) $query->where("(p.load_date != {$this->_db->q($this->dat)} or p.load_date is null)");

        $query->order($this->_db->escape($orderCol . ' ' . $orderDirn));
        $this->setState('list.limit', $limit);

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = [];
        foreach ($items as $item) {
            $arr = [];
            if (is_null($item->station_1) || is_null($item->station_2)) continue;
            $arr['id'] = $item->id;
            $arr['station_1'] = $item->station_1;
            $arr['station_2'] = $item->station_2;
            $result[$item->id] = $arr;
        }
        return $result;
    }

    protected function populateState($ordering = 'p.id', $direction = 'ASC')
    {
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        return parent::getStoreId($id);
    }

    private $export, $dat;
}
