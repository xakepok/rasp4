<?php

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

HTMLHelper::_('script', 'com_rasp/script.js', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('stylesheet', 'com_rasp/style.css', array('version' => 'auto', 'relative' => true));

?>
<div>
    <div>
        <table class="table table-dark table-stripped table-sm" style="font-size: 0.7em;">
            <thead>
            <tr>
                <th>Номер</th>
                <th>Отправление</th>
                <th>Маршрут</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->items as $thread): ?>
                <tr>
                    <td><?php echo $thread['number'];?></td>
                    <td><?php echo $thread['departure'];?></td>
                    <td><?php echo $thread['short_title'];?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    setTimeout('location.reload()', 60*1000);
</script>
