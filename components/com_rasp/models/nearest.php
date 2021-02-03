<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class RaspModelNearest extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                's.departure',
                's.id',
            );
        }
        $this->station = JFactory::getApplication()->input->getInt('station', 9601703);
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $query = $this->_db->getQuery(true);

        /* Сортировка */
        $orderCol = "s.departure";
        $orderDirn = "ASC";

        //Ограничение длины списка
        $limit = 5;

        $yandex = $this->getStationCode($this->station);

        $query
            ->select("t.number, s.departure, t.short_title")
            ->from("#__rasp_stops s")
            ->leftJoin("#__rasp_threads t on t.id = s.threadID")
            ->where("s.yandexID = {$this->_db->q($yandex)}")
            ->where("departure > current_timestamp()");

        $query->order($this->_db->escape($orderCol . ' ' . $orderDirn));
        $this->setState('list.limit', $limit);

        return $query;
    }

    private function getStationCode(int $stationID): int
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_rw/tables");
        $table = JTable::getInstance('Stations', 'TableRw');
        $table->load($stationID);
        return $table->yandex ?? 0;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = [];
        foreach ($items as $item) {
            $arr = [];
            $arr['number'] = $item->number;
            $arr['arrival'] = (!empty($item->arrival)) ? JDate::getInstance($item->arrival)->format("H.i") : '';
            $arr['departure'] = (!empty($item->departure)) ? JDate::getInstance($item->departure)->format("H.i") : '';
            $arr['short_title'] = $item->short_title;
            $result[] = $arr;
        }
        return $result;
    }

    protected function populateState($ordering = 's.departure', $direction = 'ASC')
    {
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        return parent::getStoreId($id);
    }

    private $station;
}
