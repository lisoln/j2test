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

import('sword.SwordDepositInfo');

class SwordDepositDAO extends DAO {

	/**
	 * Constructor.
	 */
	function SwordDepositDAO() {
		parent::DAO();
	}

	function &getSwordDepositInfo($contentId, $type) {
        $swordDepositInfos = array();
		$result = &$this->retrieve('SELECT * FROM sword_deposits WHERE content_id = ? AND type = ?', array($contentId, $type));
		while (!$result->EOF) {
			$swordDepositInfos[] = &$this->_returnSwordDepositInfoFromRow($result->GetRowAssoc(false));
			$result->moveNext();
		}
		$result->Close();
		unset($result);
		return $swordDepositInfos;
	}

    function insertSwordDepositInfo($type, $contentId, $targetId, $depositResponse, $ojsUserId, $targetUserName, $depositLocation) {
		$this->update('INSERT INTO sword_deposits
				(type, content_id, target_id, deposit_response, ojs_user_id, target_user_name, deposit_location)
				VALUES
				(?, ?, ?, ?, ?, ?, ?)',
			array($type, $contentId, $targetId, $depositResponse, $ojsUserId, $targetUserName, $depositLocation)
		);
	}


   function &_returnSwordDepositInfoFromRow($row) {
		$wordDepositInfo = &new SwordDepositInfo();
		$wordDepositInfo->setDepositId($row['deposit_id']);
		$wordDepositInfo->setContentType($row['type']);
		$wordDepositInfo->setContentId($row['content_id']);
		$wordDepositInfo->setDateDeposited($this->datetimeFromDB($row['date']));
		$wordDepositInfo->setTargetRepositoryId($this->datetimeFromDB($row['target_id']));
		$wordDepositInfo->setDepositResponce($row['deposit_response']);
		$wordDepositInfo->setOjsUserId($row['ojs_user_id']);
		$wordDepositInfo->setTargetUserName($row['target_user_name']);
		$wordDepositInfo->setDepositLocation($row['deposit_location']);
		return $wordDepositInfo;
	}

}

?>