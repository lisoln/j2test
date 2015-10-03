<?php
/**
 * @file SettingsForm.inc.php
 *
 * Copyright (c) 2003-2008 John Willinsky. For full terms see the file docs/COPYING.
 *
 * @class SettingsForm
 *
 * @brief Form for journal managers to list reviewers
 *
 */
import('form.Form');

class SettingsForm extends Form
{
    /** @var $journalId int */
    var $journalId;
    /** @var $plugin object */
    var $plugin;
    /** $var $errors string */
    var $errors;
    /**
     * Constructor
     * @param $journalId int
     */

    function SettingsForm(&$plugin, $journalId)
    {
        parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');
        $this->journalId = $journalId;
        $this->plugin = &$plugin;
    }
    /**
     * Initialize form data from  the plugin settings to the form
     */

    function initData()
    {
          $journalId = $this->journalId;
          $plugin = &$this->plugin;
          $selectedFields = $plugin->getSetting($journalId, 'selectedFields');
          $selectedOrderField = $plugin->getSetting($journalId, 'selectedOrderField');
          $order = $plugin->getSetting($journalId, 'order');
          $fromDate = $plugin->getSetting($journalId, 'fromDate');
          $toDate = $plugin->getSetting($journalId, 'toDate');
		if ( !isset($selectedFields) ) {
               $selectedFields = array("`users`.`first_name`", "`users`.`middle_name`", "`users`.`last_name`", "`users`.`country`", "COUNT(*) AS CN");
               $selectedOrderField = array("COUNT(*) AS CN", "`users`.`last_name`", "`users`.`first_name`", "", "", "", "", "", "", "");
               $order = array('DESC', 'ASC', 'ASC', 'ASC', 'ASC', 'ASC', 'ASC', 'ASC', 'ASC', 'ASC');
               $fromDate = date('Y-m-d', strtotime('last year'));
               $toDate = date('Y-m-d');
		}
		$this->setData('selectedFields', $selectedFields);
		$this->setData('selectedOrderField', $selectedOrderField);
		$this->setData('order', $order);
		$this->setData('fromDate', $fromDate);
		$this->setData('toDate', $toDate);
          $templateMgr = &TemplateManager::getManager();
    }
    /**
     * Assign form data to user-submitted data.
     */

    function readInputData()
    {
        $this->readUserVars(array(
            'listReviewers',
            'selectedFields',
            'selectedOrderField',
            'order'
        ));
        $this->_data['fromDate'] = Request::getUserDateVar('fromDate', 1, 1);
        if ($this->_data['fromDate'] !== null) $this->_data['fromDate'] = date('Y-m-d', $this->_data['fromDate']);
        $this->_data['toDate'] = Request::getUserDateVar('toDate', 1, 1);
        if ($this->_data['toDate'] !== null) $this->_data['toDate'] = date('Y-m-d', $this->_data['toDate']);
    }

	/**
	 * Update the plugin settings
	 */
	function execute() {
		$plugin =& $this->plugin;
		$journalId = $this->journalId;

		$pluginSettingsDAO =& DAORegistry::getDAO('PluginSettingsDAO');

		// Update data
		$selectedFields = $this->getData('selectedFields');
		$selectedOrderField = $this->getData('selectedOrderField');
		$order = $this->getData('order');
		$fromDate = $this->getData('fromDate');
		$toDate = $this->getData('toDate');

		$plugin->updateSetting($journalId, 'selectedFields', $selectedFields);
		$plugin->updateSetting($journalId, 'selectedOrderField', $selectedOrderField);
		$plugin->updateSetting($journalId, 'order', $order);
		$plugin->updateSetting($journalId, 'fromDate', $fromDate);
		$plugin->updateSetting($journalId, 'toDate', $toDate);
		//$this->setData('icons',$icons);
	}

}
?>
