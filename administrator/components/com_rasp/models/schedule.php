<?php
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\Model\ListModel;

class RaspModelSchedule extends BaseDatabaseModel
{
    public function __construct($config = array())
    {
        $this->dat = JDate::getInstance('now + 3 hour')->modify("+1 day")->format("Y-m-d");
        $this->apikey = RaspHelper::getConfig('yarasp_api_key');
        parent::__construct($config);
    }

    public function getThreads(): string
    {
        $points = $this->getPoints();
        if (empty($points)) return 'No stations for search';

        $uid_list = $this->getExistsThreads($this->dat); //Существующие UID ниток на нужную дату
        $uids = [];

        $yandex = new YandexRasp($this->apikey);
        $loaded_points = [];

        foreach ($points as $pid => $point) {
            $rasp = $yandex->getRaspBetweenStations($point['station_1'], $point['station_2'], $this->dat);
            if ($rasp['result']) {
                foreach ($rasp['data']['threads'] as $i => $thread) {
                    if (array_search($thread['thread']['uid'], $uid_list) === false) {
                        array_push($uid_list, $thread['thread']['uid']);
                        $uids[$thread['thread']['uid']] = $thread;
                    }
                }
                $loaded_points[] = $pid;
            }
        }
        if (!empty($uids)) RaspHelper::insertThreads($uids, $this->dat);
        if (!empty($loaded_points)) RaspHelper::setLoadedPoints($loaded_points, $this->dat);
        return 'ok';
    }

    public function getStops(): string
    {
        $threads = $this->getThreadsToLoad();
        if (empty($threads)) return 'No threads to load';

        $yandex = new YandexRasp($this->apikey);
        $loaded_threads = []; //ID загруженных ниток
        $stops = [];
        foreach ($threads as $id => $thread) {
            $info = $yandex->getThread($thread['uid'], $thread['date']);
            if (!$info['result']) return $info['result'];
            $loaded_threads[] = $id;
            if ($info['data']['transport_type'] == 'train') continue;
            foreach ($info['data']['stops'] as $stop) {
                $arr = [];
                $arr['yandexID'] = str_ireplace('s', '', $stop['station']['code']);
                $arr['arrival'] = $stop['arrival'];
                $arr['departure'] = $stop['departure'];
                $arr['stop_time'] = $stop['stop_time'];
                $arr['terminal'] = $stop['terminal'];
                $arr['platform'] = $stop['platform'];
                $stops[$id][] = $arr;
            }
        }
        RaspHelper::insertStops($stops);
        RaspHelper::setLoadedItems($loaded_threads);
        return 'ok';
    }

    private function getPoints()
    {
        $model = ListModel::getInstance('Points', 'RaspModel', ['date' => $this->dat]);
        return $model->getItems();
    }

    private function getExistsThreads(string $dat = '')
    {
        $model = ListModel::getInstance('Threads', 'RaspModel', ['date' => $dat]);
        return $model->getItems();
    }

    private function getThreadsToLoad()
    {
        $model = ListModel::getInstance('Threads', 'RaspModel', ['not_load' => true]);
        return $model->getItems();
    }

    private $dat, $apikey;
}