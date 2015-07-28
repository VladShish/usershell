<?PHP

/**
 * Author: Vlad Shish <vladshish89@gmail.com>
 * Date: 17.10.14
 * Time: 16:41
 * Format: http://book.cakephp.org/2.0/en/console-and-shells.html#creating-a-shell
 */
App::uses('UsersOperator', 'UserShell.Lib');
/**
 * User shell
 * 
 * @package SnatzDM
 * @subpackage User
 */
class UserShell extends Shell {

	/**
	 * GroupOperator instance
	 */
	protected $_UsersOperator;

	/**
	 * Constructor
	 */
	public function __construct($stdout = null, $stderr = null, $stdin = null) {
		parent::__construct($stdout, $stderr, $stdin);
		$this->_UsersOperator = new UsersOperator();
	}

	/**
	 * Start up And load Acl Component / Aco model
	 *
	 * @return void
	 **/
	public function startup() {
		parent::startup();
		$this->_UsersOperator->startup();
		$this->stdout->styles('ok', array('text' => 'green', 'blink' => true));
	}

	/**
	 * Init groups
	 */
	public function create() {
		if ($this->_UsersOperator->create()) {
			$this->out('<ok>' . $this->_UsersOperator->getSuccessMessages() . '<ok>');
		} else {
			$this->out('<warning>' . $this->_UsersOperator->getErrorMessages() . '</warning>');
		}
	}

	/**
	 * Init groups
	 */
	public function complete() {
		if ($this->_UsersOperator->complete()) {
			$this->out('<ok>' . $this->_UsersOperator->getSuccessMessages() . '<ok>');
		} else {
			$this->out('<warning>' . $this->_UsersOperator->getErrorMessages() . '</warning>');
		}
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return ConsoleOptionParser
	 */
	public function getOptionParser() {
		return parent::getOptionParser()
			->description(__("Task for create, update groups from config"))
			->addSubcommand('create', array(
				'parser' => array(
					'options' => array(),
				),
				'help' => __('Create users')
			))
			->addSubcommand('complete', array(
				'parser' => array(
					'options' => array(),
				),
				'help' => __('Complete users')
			));
	}

}