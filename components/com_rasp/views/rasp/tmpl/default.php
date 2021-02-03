<?php
/**
 * @package    rasp
 *
 * @author     anton@nazvezde.ru <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;

/** @var RaspViewRasp $this */

HTMLHelper::_('script', 'com_rasp/script.js', ['version' => 'auto', 'relative' => true]);
HTMLHelper::_('stylesheet', 'com_rasp/style.css', ['version' => 'auto', 'relative' => true]);

$layout       = new FileLayout('rasp.page');
$data         = [];
$data['text'] = 'Hello Joomla!';
echo $layout->render($data);
