<?php

/**
 * User: vlad
 * Date: 27.07.15
 * Time: 15:51
 */
abstract class AOperator extends Object {

	/**
	 * @var string
	 */
	protected $_successMessage;

	/**
	 * @var string
	 */
	protected $_errorMessage;

	/**
	 * @return string
	 */
	public function getSuccessMessages() {
		return $this->_successMessage;
	}

	/**
	 * @param string $successMessage
	 */
	public function setSuccessMessage($successMessage) {
		$this->_successMessage = $successMessage;
	}

	/**
	 * @return string
	 */
	public function getErrorMessages() {
		return $this->_errorMessage;
	}

	/**
	 * @param string $errorMessage
	 */
	public function setErrorMessage($errorMessage) {
		$this->_errorMessage = $errorMessage;
	}

}