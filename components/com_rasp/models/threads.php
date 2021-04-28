<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class RaspModelThreads extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                't.id',
            );
        }
        $this->search = $config['search'] ?? 0;
        parent::__construct($config);
    }

    protected function _getListQuery()
    {
        $query = $this->_db->getQuery(true);

        /* Сортировка */
        $orderCol = "t.id";
        $orderDirn = "DESC";

        //Ограничение длины списка
        $limit = 15;

        $text = $this->_db->q((is_numeric($this->search)) ? $this->search : $this->search ."%");

        $query
            ->select("t.id, t.number, t.start_date, t.short_title")
            ->select("date_format(sum(timestamp(ifnull(s.departure, 0))), '%Y-%m-%d %H:%i:%s') as time_start")
            ->select("date_format(sum(timestamp(ifnull(s.arrival, 0))), '%Y-%m-%d %H:%i:%s') as time_end")
            ->select("timediff(sum(timestamp(ifnull(s.arrival, 0))), sum(timestamp(ifnull(s.departure, 0)))) as diff")
            ->select("time_to_sec(timediff(sum(timestamp(ifnull(s.arrival, 0))), sum(timestamp(ifnull(s.departure, 0))))) as diff_sec")
            ->from("#__rasp_threads t")
            ->leftJoin("#__rasp_stops s on t.id = s.threadID and (s.arrival is null or s.departure is null)")
            ->where("t.start_date > curdate()")
            ->where("(t.number = {$text} OR t.title LIKE {$text})")
            ->group("t.id, t.number, t.short_title")
            ->having("time_start is not null and time_end is not null");

        $query->order($this->_db->escape($orderCol . ' ' . $orderDirn));
        $this->setState('list.limit', $limit);

        return $query;
    }

    protected function populateState($ordering = 't.id', $direction = 'DESC')
    {
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        return parent::getStoreId($id);
    }

    private $search;
}
