<?php

/**
 * @file classes/currency/CurrencyDAO.inc.php
 *
 * Copyright (c) 2003-2008 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class CSwordDepositDAO
 * @ingroup sword
 * @see sword
 *
 * @brief Operations for retrieving and modifying Currency objects.
 *
 */

import('sword.SwordDepositTargetInfo');

class SwordDepositTargetDAO extends DAO {

	/**
	 * Constructor.
	 */
	function SwordDepositTargetDAO() {
		parent::DAO();
	}

	function getSwordDepositTargets() {
        $SwordDepositTargetInfos = array();
		$result = &$this->retrieve('SELECT * FROM sword_deposit_targets');
        $i = 0;
		while (!$result->EOF) {
			$SwordDepositTargetInfos[$i++] = &$this->returnSwordDepositTargetInfoFromRow($result->GetRowAssoc(false));
			$result->moveNext();
		}
		$result->Close();
		unset($result);
		return $SwordDepositTargetInfos;
	}

	function getSwordDepositTarget($targetId) {
		$result = &$this->retrieve('SELECT * FROM sword_deposit_targets WHERE target_id= ?', $targetId);
		$SwordDepositTargetInfo = &$this->returnSwordDepositTargetInfoFromRow($result->GetRowAssoc(false));
		$result->Close();
		unset($result);
		return $SwordDepositTargetInfo;
	}

	function updateSwordDepositTarget($targetId, $targetUri, $type, $title, $description, $formatNamespace) {
		$returner = $this->update(
			'UPDATE sword_deposit_targets
				SET target_uri = ?, type = ?, title = ?, description = ?, format_ns = ?
				WHERE target_id= ?',
			array($targetUri, $type, $title, $description, $formatNamespace, $targetId));
		return $returner;
	}


    function insertSwordDepositInfo($targetUri, $type, $title, $description, $formatNamespace) {
		$this->update('INSERT INTO sword_deposit_targets (target_uri, type, title, description, format_ns) VALUES(?, ?, ?, ?, ?)',
			array($targetUri, $type, $title, $description, $formatNamespace));
	}

    function deleteSwordDepositTarget($targetId) {
		$this->update('DELETE FROM sword_deposit_targets WHERE target_id = ?',
			array($targetId));
	}



	function &returnSwordDepositTargetInfoFromRow(&$row) {
		$SwordDepositTargetInfo = &new SwordDepositTargetInfo();
		$this->_swordDepositTargetInfoFromRow($SwordDepositTargetInfo, $row);
		return $SwordDepositTargetInfo;
	}

	function &getNewSwordDepositTargetInfo() {
		$SwordDepositTargetInfo = &new SwordDepositTargetInfo();
		return $SwordDepositTargetInfo;
	}

   function &_swordDepositTargetInfoFromRow($SwordDepositTargetInfo, $row) {
		$SwordDepositTargetInfo->setTargetId($row['target_id']);
		$SwordDepositTargetInfo->setTargetUri($row['target_uri']);
		$SwordDepositTargetInfo->setTargetType($row['type']);
		$SwordDepositTargetInfo->setTargetTitle($row['title']);
		$SwordDepositTargetInfo->setTargetDescription($row['description']);
		$SwordDepositTargetInfo->setFormatNamespace($row['format_ns']);
		HookRegistry::call('SwordDepositTargetDAO::_returnSwordDepositTargetInfoFromRow', array(&$SwordDepositTargetInfo, &$row));
		return $SwordDepositTargetInfo;
	}

}

?>
