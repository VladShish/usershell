<?php
App::uses('AOperator', 'UserShell.Lib');

/**
 * User: vlad
 * Date: 27.07.15
 * Time: 15:34
 */
class UsersOperator extends AOperator {

	protected $_User;

	/**
	 * Start up And load Acl Component
	 *
	 * @return void
	 **/
	public function startup() {
		$userModel = Configure::check('Users.modelName') ? Configure::read('Users.modelName') : 'User';
		$this->_User = ClassRegistry::init($userModel);
	}

	public function create() {
		if ($this->_User->find('count')) {
			$this->setErrorMessage(__('Some users already exists'));
			return false;
		}

		foreach (Configure::read('Users.data') as $user) {
			$this->_User->save($user, array('validate' => false));
		}

		$this->setSuccessMessage(__('Users has been created'));
		return true;
	}

	public function complete() {
		if ($this->_checkComplete()) {
			return false;
		}

		$this->_completeUsers();
		return true;
	}

	public function update() {
		if ($this->_checkComplete()) {
			return false;
		}

		$this->_updateUsers();
		return true;
	}

	protected function _checkComplete() {
		if (!Configure::check('Users.data')) {
			$this->setErrorMessage(__('Users configure settings is absent'));
			return true;
		}

		return false;
	}

	protected function _completeUsers() {
		foreach (Configure::read('Users.data') as $user) {
			if (!$this->_isUserExists($user)) {
				$this->_User->save($user);
			}
		}

		$this->setSuccessMessage(__('Users has been completed'));
	}

	protected function _updateUsers() {
		$users = Configure::read('Users.data');
		foreach ($users as $user) {
			$this->_User->save($user);
		}

		$this->setSuccessMessage(__('All Users has been updated'));
	}

	protected function _isUserExists($user) {
		return (boolean)$this->_User->find('count',
			array(
				'conditions' => [$this->_User->alias . '.id' => $user['id']]
			) +
			Configure::read('Users.conditions')
		);
	}

}