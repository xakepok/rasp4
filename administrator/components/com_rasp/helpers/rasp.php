<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

class RaspHelper
{
	public function addSubmenu($vName)
	{
		HTMLHelper::_('sidebar.addEntry', JText::sprintf('COM_RASP'), 'index.php?option=com_rasp&view=rasps', $vName === 'rasps');
	}

    public static function insertThreads(array $uids, string $date)
    {
        $db = JFactory::getDbo();
        $table = $db->qn("#__rasp_threads");
        $columns = $db->qn(['id', 'dat', 'uid', 'title', 'short_title', 'number', 'start_date', 'load_time']);
        $query = $db->getQuery(true);
        $query
            ->insert($table)
            ->columns($columns);
        foreach ($uids as $uid => $thread) {
            $short = (!$thread['thread']['short_title']) ? null : $db->q($thread['thread']['short_title']);
            $start = $db->q(JDate::getInstance($thread['start_date'])->format("Y-m-d"));
            $query->values("null, {$db->q($date)}, {$db->q($uid)}, {$db->q($thread['thread']['title'])}, {$short}, {$db->q($thread['thread']['number'])}, {$start}, null");
        }

        $db->setQuery($query)->execute();
	}

    public static function insertStops(array $stops)
    {
        $db = JFactory::getDbo();
        $table = $db->qn("#__rasp_stops");
        $columns = $db->qn(['id', 'threadID', 'yandexID', 'arrival', 'departure', 'stop_time', 'terminal', 'platform']);
        $query = $db->getQuery(true);
        $query
            ->insert($table)
            ->columns($columns);
        $cnt = 0;
        foreach ($stops as $threadID => $stations) {
            $yuid = [];
            foreach ($stations as $station) {
                $yuid[] = $station['yandexID'];
                $arrival = (!empty($station['arrival'])) ? $db->q($station['arrival']) : 'null';
                $departure = (!empty($station['departure'])) ? $db->q($station['departure']) : 'null';
                $terminal = (!empty($station['terminal'])) ? $db->q($station['terminal']) : 'null';
                $platform = (!empty($station['platform'])) ? $db->q($station['platform']) : 'null';
                $stop_time = $db->q($station['stop_time'] ?? 0);
                $query->values("null, {$db->q($threadID)}, {$db->q($station['yandexID'])}, {$arrival}, {$departure}, {$stop_time}, {$terminal}, {$platform}");
                $cnt++;
            }
        }
        if ($cnt > 0) $db->setQuery($query)->execute();
	}

    public static function setLoadedItems(array $ids)
    {
        if (empty($ids)) return;
        $db = JFactory::getDbo();
        $table = $db->qn("#__rasp_threads");
        $query = $db->getQuery(true);
        $ids = implode(', ', $ids);
        if (empty($ids)) return;
        $query
            ->update($table)
            ->set("{$db->qn('load_time')} = {$db->q(JDate::getInstance()->toSql())}")
            ->where("{$db->qn('id')} in ({$ids})");
        $db->setQuery($query)->execute();
	}

    public static function setLoadedPoints(array $ids, string $dat)
    {
        if (empty($ids)) return;
        $db = JFactory::getDbo();
        $table = $db->qn("#__rasp_points");
        $query = $db->getQuery(true);
        $ids = implode(', ', $ids);
        if (empty($ids)) return;
        $query
            ->update($table)
            ->set("{$db->qn('load_date')} = {$db->q($dat)}")
            ->where("{$db->qn('id')} in ({$ids})");
        $db->setQuery($query)->execute();
	}

    public static function getConfig(string $param, $default = null)
    {
        $config = JComponentHelper::getParams("com_rasp");
        return $config->get($param, $default);
    }
}
