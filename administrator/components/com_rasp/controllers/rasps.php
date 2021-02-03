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

use Joomla\CMS\MVC\Controller\AdminController;

/**
 * Rasps Controller.
 *
 * @package  rasp
 * @since    1.0.0
 */
class RaspControllerRasps extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $text_prefix = 'com_rasp_rasp';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  \JModelLegacy  The model.
	 *
	 * @since   1.0.0
	 */
	public function getModel(
		$name = 'Rasp',
		$prefix = 'RaspsModel',
		$config = ['ignore_request' => true]
	) {
		return parent::getModel($name, $prefix, $config);
	}
}
