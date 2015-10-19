<?php


import('lib.pkp.classes.db.DAO');

class LiqPaylDAO extends DAO {
	/**
	 * Constructor.
	 */
	function LiqPaylDAO() {
		parent::DAO();
	}

	/**
	 * Insert a payment into the payments table
	 * @param $txnId string
	 * @param $txnType string
	 * @param $payerEmail string
	 * @param $receiverEmail string
	 * @param $itemNumber string
	 * @param $paymentDate datetime
	 * @param $payerId string
	 * @param $receiverId string
	 */
	 function insertTransaction($txnId, $txnType, $payerEmail, $receiverEmail, $itemNumber, $paymentDate, $payerId, $receiverId) {
		$ret = $this->update(
			sprintf(
				'INSERT INTO liqpay_transactions (
					txn_id,
					txn_type,
					payer_email,
					receiver_email,
					item_number,
					payment_date,
					payer_id,
					receiver_id
				) VALUES (
					?, ?, ?, ?, ?, %s, ?, ?
				)',
				$this->datetimeToDB($paymentDate)
			),
			array(
				$txnId,
				$txnType,
				$payerEmail,
				$receiverEmail,
				$itemNumber,
				$payerId,
				$receiverId
			)
		);

		return true;
	 }

	/**
	 * Check whether a given transaction exists.
	 * @param $txnId string
	 * @return boolean
	 */
	function transactionExists($txnId) {
		$result =& $this->retrieve(
			'SELECT	count(*) FROM liqpay_transactions WHERE txn_id = ?',
			array($txnId)
		);

		$returner = false;
		if (isset($result->fields[0]) && $result->fields[0] >= 1) $returner = true;

		$result->Close();
		return $returner;
	}
}

?>
