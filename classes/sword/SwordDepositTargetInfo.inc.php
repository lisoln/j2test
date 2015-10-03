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



class SwordDepositTargetInfo extends DataObject {

	function SwordDepositTargetInfo() {
		parent::DataObject();
	}

	function getTargetId() {
		return $this->getData('target_id');
	}
	function setTargetId($targetId) {
		return $this->setData('target_id', $targetId);
	}

	function getTargetType() {
		return $this->getData('type');
	}
	function setTargetType($contentType) {
		return $this->setData('type', $contentType);
	}

	function getTargetUri() {
		return $this->getData('target_uri');
	}

	function setTargetUri($targetUri) {
		return $this->setData('target_uri', $targetUri);
	}
	
	function getTargetTitle() {
		return $this->getData('title');
	}
	function setTargetTitle($title) {
		return $this->setData('title', $title);
	}

	function getTargetDescription() {
		return $this->getData('description');
	}
	function setTargetDescription($description) {
		return $this->setData('description', $description);
	}

	function getFormatNamespace() {
		return $this->getData('format_ns');
	}

	function setFormatNamespace($formatNamespace) {
		return $this->setData('format_ns', $formatNamespace);
	}


}

?>
