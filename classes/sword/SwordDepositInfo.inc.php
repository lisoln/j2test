<?php

/**
 * @defgroup issue
 */
 
/**
 * @file classes/issue/SwordDepositInfo.inc.php
 *
 * Copyright (c) 2003-2008 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SwordDeposits
 * @ingroup sword
 * @see SwordDepositsDAO
 *
 * @brief Class for SwordDepositsDAO.
 */



class SwordDepositInfo extends DataObject {

	function SwordDepositInfo() {
		parent::DataObject();
	}

	function getDepositId() {
		return $this->getData('deposit_id');
	}
	function setDepositId($depositId) {
		return $this->setData('deposit_id', $depositId);
	}


	function getContentType() {
		return $this->getData('type');
	}
	function setContentType($contentType) {
		return $this->setData('type', $contentType);
	}



	function getContentId() {
		return $this->getData('content_id');
	}
	function setContentId($contentId) {
		return $this->setData('content_id', $contentId);
	}

	
	function getDateDeposited() {
		return $this->getData('date');
	}
	function setDateDeposited($dateDeposited) {
		return $this->setData('date', $dateDeposited);
	}


	function getTargetRepositoryId() {
		return $this->getData('target_id');
	}
	function setTargetRepositoryId($targetId) {
		return $this->setData('target_id', $targetId);
	}


	function getDepositResponce() {
		return $this->getData('target_id');
	}
	function setDepositResponce($targetId) {
		return $this->setData('target_id', $targetId);
    }

	function getOjsUserId() {
		return $this->getData('ojs_user_id');
	}
	function setOjsUserId($ojsUserId) {
		return $this->setData('ojs_user_id', $ojsUserId);
    }
	
    function getTargetUserName() {
		return $this->getData('target_user_name');
	}

	function setTargetUserName($targetUserName) {
		return $this->setData('target_user_name', $targetUserName);
	}

    function getDepositLocation() {
		return $this->getData('deposit_location');
	}

	function setDepositLocation($depositLocation) {
		return $this->setData('deposit_location', $depositLocation);
	}



}
?>