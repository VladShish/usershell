<?php
App::uses('ComponentCollection', 'Controller');
App::uses('Controller', 'Controller');
App::uses('AclComponent', 'Controller/Component');
App::uses('AOperator', 'UserShell.Lib');

/**
 * User: vlad
 * Date: 24.07.15
 * Time: 15:54
 */
class GroupsOperator extends AOperator {

	protected $_Acl;

	protected $_Group;

	/**
	 * Start up And load Acl Component
	 *
	 * @return void
	 **/
	public function startup() {
		$collection = new ComponentCollection();
		$this->_Acl = new AclComponent($collection);
		$controller = new Controller(new CakeRequest());
		$this->_Acl->initialize($controller);
		$groupModel = Configure::check('Groups.modelName') ? Configure::read('Groups.modelName') : 'Group';
		$this->_Group = ClassRegistry::init($groupModel);
	}

	public function init() {
		if ($this->_checkInit()) {
			return false;
		}

		$this->_createGroups();
		return true;
	}

	public function complete() {
		if ($this->_checkComplete()) {
			return false;
		}

		$this->_completeGroups();
		return true;
	}

	public function setPermissions() {
		if (!$this->_Group->find('count')) {
			$this->setErrorMessage(__('No one groups was found'));
			return false;
		}

		$this->_assignPermissions();
		return true;
	}

	/**
	 * @return bool
	 */
	protected function _checkInit() {
		if ($this->_Group->find('count')) {
			$this->setErrorMessage('Groups already exists');
			return true;
		} elseif (!Configure::check('Groups.data')) {
			$this->setErrorMessage('Groups configure settings is absent');
			return true;
		}

		return false;
	}

	protected function _checkComplete() {
		if (!Configure::check('Groups.data')) {
			$this->setErrorMessage(__('Groups configure settings is absent'));
			return true;
		}

		return false;
	}

	/**
	 * Create main groups
	 */
	protected function _createGroups() {
		foreach (Configure::read('Groups.data') as $data) {
			$this->_Group->create();
			$this->_Group->save($data);
		}

		$this->setSuccessMessage('Groups has been created');
	}

	protected function _completeGroups() {
		foreach (Configure::read('Groups.data') as $group) {
			if (!$this->_isGroupExists($group)) {
				$this->_Group->save($group);
			}
		}

		$this->setSuccessMessage(__('Groups has been completed'));
	}

	protected function _isGroupExists($group) {
		return (boolean)$this->_Group->find('count', array('conditions' => array(
			'id' => $group['id'],
		)));
	}

	protected function _assignPermissions() {
		foreach (Configure::read('Groups.permissions') as $rights) {
			$groupsIds = $this->_getGroupsIds($rights);
			foreach ($groupsIds as $groupId) {
				$this->_Group->id = $groupId;
				$this->_setDenyPermissions($rights['deny']);
				$this->_setAllowPermissions($rights['allow']);
			}
		}
		$this->setSuccessMessage(__('Permissions exhibited'));
	}

	protected function _getGroupsIds($rights) {
		if (array_key_exists('id !=', $rights)) {
			return $this->_Group->find('list', array(
				'fields' => ['id', 'id'],
				'conditions' => ['id !=' => $rights['id !=']]
			));
		}

		return (array)Hash::get($rights, 'id');
	}

	/**
	 * Set deny permission for members groups
	 * @param $acos
	 */
	protected function _setDenyPermissions($acos) {
		foreach ($acos as $aco) {
			$this->_Acl->deny($this->_Group, $aco);
		}
	}

	/**
	 * Set allow permission for members groups
	 * @param $acos
	 */
	protected function _setAllowPermissions($acos) {
		foreach ($acos as $aco) {
			$this->_Acl->allow($this->_Group, $aco);
		}
	}

}