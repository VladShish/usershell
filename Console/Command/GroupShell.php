<?PHP

/**
 * Author: Vlad Shish <vladshish89@gmail.com>
 * Date: 17.10.14
 * Time: 16:41
 * Format: http://book.cakephp.org/2.0/en/console-and-shells.html#creating-a-shell
 */
App::uses('GroupsOperator', 'UserShell.Lib');
/**
 * Group shell
 */
class GroupShell extends Shell {

	/**
	 * GroupOperator instance
	 */
	protected $_GroupsOperator;

	/**
	 * Constructor
	 */
	public function __construct($stdout = null, $stderr = null, $stdin = null) {
		parent::__construct($stdout, $stderr, $stdin);
		$this->_GroupsOperator = new GroupsOperator();
	}

	/**
	 * Start up And load Acl Component / Aco model
	 *
	 * @return void
	 **/
	public function startup() {
		parent::startup();
		$this->_GroupsOperator->startup();
		$this->stdout->styles('ok', array('text' => 'green', 'blink' => true));
	}

	/**
	 * Init groups
	 */
	public function init() {
		if ($this->_GroupsOperator->init()) {
			$this->out('<ok>' . $this->_GroupsOperator->getSuccessMessages() . '<ok>');
		} else {
			$this->out('<warning>' . $this->_GroupsOperator->getErrorMessages() . '</warning>');
		}
	}

	/**
	 * complite groups
	 */
	public function complete() {
		if ($this->_GroupsOperator->complete()) {
			$this->out('<ok>' . $this->_GroupsOperator->getSuccessMessages() . '<ok>');
		} else {
			$this->out('<warning>' . $this->_GroupsOperator->getErrorMessages() . '</warning>');
		}
	}

	/**
	 * Set groups permissions
	 */
	public function set_permissions() {
		if ($this->_GroupsOperator->setPermissions()) {
			$this->out('<ok>' . $this->_GroupsOperator->getSuccessMessages() . '<ok>');
		} else {
			$this->out('<warning>' . $this->_GroupsOperator->getErrorMessages() . '</warning>');
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
			->addSubcommand('init', array(
				'parser' => array(
					'options' => array(),
				),
				'help' => __('Init groups')
			))
			->addSubcommand('complete', array(
				'parser' => array(
					'options' => array(),
				),
				'help' => __('complete groups')
			))->addSubcommand('set_permissions', array(
				'parser' => array(
					'options' => array(),
				),
				'help' => __('Initialization groups permissions')
			));
	}

}
