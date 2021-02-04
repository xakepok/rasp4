<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class RaspModelStops extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                's.arrival, s.departure',
                's.id',
            );
        }
        $this->station = JFactory::getApplication()->input->getInt('station', 9601703);
        $this->threadID = JFactory::getApplication()->input->getInt('threadID', 0);
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $query = $this->_db->getQuery(true);

        /* Сортировка */
        $orderCol = "s.arrival, s.departure";
        $orderDirn = "ASC";

        //Ограничение длины списка
        $limit = 0;

        $query
            ->select("if(s.stop_time != 0, s.arrival, if(s.departure is not null, '', s.arrival)) as arrival, if(s.stop_time != 0, s.departure, if(s.arrival is not null, '', s.departure)) as departure, s.platform")
            ->select("rs.title as station")
            ->from("#__rasp_stops s")
            ->leftJoin("#__rw_stations rs on rs.yandex = s.yandexID")
            ->where("s.threadID = {$this->_db->q($this->threadID)}");

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
            $arr['platform'] = $item->platform;
            $arr['arrival'] = (!empty($item->arrival)) ? JDate::getInstance($item->arrival)->format("H.i") : '-';
            $arr['departure'] = (!empty($item->departure)) ? JDate::getInstance($item->departure)->format("H.i") : '-';
            $arr['station'] = $item->station;
            $result[] = $arr;
        }
        return $result;
    }

    protected function populateState($ordering = 's.arrival, s.departure', $direction = 'ASC')
    {
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        return parent::getStoreId($id);
    }

    private $station, $threadID;
}
