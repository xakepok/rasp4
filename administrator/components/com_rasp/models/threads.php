<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class RaspModelThreads extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'u.id',
            );
        }
        parent::__construct($config);
        $input = JFactory::getApplication()->input;
        $this->export = ($input->getString('format', 'html') === 'html') ? false : true;
        $this->dat = $config['date'] ?? '';
        $this->not_load = $config['not_load'] ?? false;
    }

    protected function _getListQuery()
    {
        $query = $this->_db->getQuery(true);

        /* Сортировка */
        $orderCol = "u.id";
        $orderDirn = "ASC";

        //Ограничение длины списка
        $limit = 0;

        $query
            ->select("u.uid")
            ->from("#__rasp_threads u");

        //Уже загруженные UID на нужную дату
        if (!empty($this->dat)) {
            $dat = $this->_db->q($this->dat);
            $query->where("u.dat = {$dat}");
        }

        //Не загруженные нитки
        if ($this->not_load) {
            $query
                ->select("u.id, u.start_date")
                ->where("u.load_time is null");
            $limit = RaspHelper::getConfig('count_threads', 100);
        }

        $query->order($this->_db->escape($orderCol . ' ' . $orderDirn));
        $this->setState('list.limit', $limit);

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = [];
        foreach ($items as $item) {
            if (!$this->not_load) {
                $result[] = $item->uid;
            }
            else {
                $result[$item->id] = ['uid' => $item->uid, 'date' => $item->start_date];
            }
        }
        return $result;
    }

    protected function populateState($ordering = 'u.id', $direction = 'ASC')
    {
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        return parent::getStoreId($id);
    }

    private $export, $dat, $not_load;
}
