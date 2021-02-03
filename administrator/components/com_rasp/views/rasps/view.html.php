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

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Registry\Registry;

/**
 * Rasp view.
 *
 * @package  rasp
 * @since    1.0.0
 */
class RaspViewRasps extends HtmlView
{
	/**
	 * Array with profiles
	 *
	 * @var    array
	 * @since  1.0.0
	 */
	protected $items = [];

	/**
	 * The model state
	 *
	 * @var    Registry
	 * @since  1.0.0
	 */
	protected $state;

	/**
	 * Pagination object
	 *
	 * @var    Pagination
	 * @since  1.0.0
	 */
	protected $pagination;

	/**
	 * Companies helper
	 *
	 * @var    RaspHelper
	 * @since  1.0.0
	 */
	protected $helper;

	/**
	 * The sidebar to show
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $sidebar = '';

	/**
	 * Form with filters
	 *
	 * @var    Form
	 * @since  1.0.0
	 */
	public $filterForm;

	/**
	 * List of active filters
	 *
	 * @var    array
	 * @since  1.0.0
	 */
	public $activeFilters = [];

	/**
	 * Actions registry
	 *
	 * @var    Registry
	 * @since  1.0.0
	 */
	protected $canDo;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.0.0
	 *
	 * @throws Exception
	 *
	 * @see    fetch()
	 */
	public function display($tpl = null)
	{
		/** @var RaspModelRasps $model */
		$model               = $this->getModel();
		$this->items         = $model->getItems();
		$this->state         = $model->getState();
		$this->pagination    = $model->getPagination();
		$this->filterForm    = $model->getFilterForm();
		$this->activeFilters = $model->getActiveFilters();
		$this->canDo         = ContentHelper::getActions('com_rasp');

		// Show the toolbar
		$this->toolbar();

		// Show the sidebar
		$this->helper = new RaspHelper;
		$this->helper->addSubmenu('rasps');
		$this->sidebar = JHtmlSidebar::render();

		// Display it all
		return parent::display($tpl);
	}

	/**
	 * Displays a toolbar for a specific page.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	private function toolbar()
	{
		ToolbarHelper::title(Text::_('COM_RASP_RASP'), '');

		if ($this->canDo->get('core.create'))
		{
			ToolbarHelper::addNew('rasp.add');
		}

		if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own'))
		{
			ToolbarHelper::editList('rasp.edit');
		}

		if ($this->canDo->get('core.edit.state'))
		{
			ToolbarHelper::publish('rasps.publish', 'JTOOLBAR_PUBLISH', true);
			ToolbarHelper::unpublish('rasps.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			ToolbarHelper::archiveList('rasps.archive');
		}

		if ((int) $this->state->get('filter.published') === -2 && $this->canDo->get('core.delete'))
		{
			ToolbarHelper::deleteList(
				'JGLOBAL_CONFIRM_DELETE',
				'rasps.delete',
				'JTOOLBAR_EMPTY_TRASH'
			);
		}
		elseif ($this->canDo->get('core.edit.state'))
		{
			ToolbarHelper::trash('rasps.trash');
		}

		// Options button.
		if (Factory::getUser()->authorise('core.admin', 'com_rasp'))
		{
			ToolbarHelper::preferences('com_rasp');
		}
	}
}
