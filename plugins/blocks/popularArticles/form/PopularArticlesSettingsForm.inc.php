<?php

/**
 * @file plugins/blocks/popularArticles/PopularArticlesSettingsForm.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PopularArticlesSettingsForm
 * @ingroup plugins_blocks_popularArticles
 *
 * @brief Form for journal managers to modify popular articles plugin settings.
 */

import('lib.pkp.classes.form.Form');

class PopularArticlesSettingsForm extends Form {

	/** @var $plugin object */
	var $_plugin;

	/** @var $journal Journal */
	var $_journal;

	/**
	 * Constructor
	 * @param $plugin PKPPlugin
	 * @param $journal Journal
	 */
	function PopularArticlesSettingsForm(&$plugin, &$journal) {
		$this->_plugin =& $plugin;
		$this->_journal =& $journal;

		parent::Form($plugin->getTemplatePath() . 'popularArticlesSettingsForm.tpl');
		$this->addCheck(new FormValidatorPost($this));
	}

	/**
	 * @copydoc Form::initData()
	 */
	function initData() {
		$plugin =& $this->_plugin;
		$journal =& $this->_journal;
		$this->setData('maxArticlesCount', $plugin->getSetting($journal->getId(), 'maxArticlesCount'));
		$this->setData('months', $plugin->getSetting($journal->getId(), 'months'));
		$this->setData('mixOldStats', $plugin->getSetting($journal->getId(), 'mixOldStats'));
	}

	/**
	 * @copydoc Form::readInputData()
	 */
	function readInputData() {
		$this->readUserVars(array('maxArticlesCount', 'months', 'mixOldStats'));
	}

	/**
	 * @copydoc Form::display()
	 */
	function display($request) {
		$templateMgr =& TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		parent::display();
	}

	/**
	 * @copydoc Form::execute()
	 */
	function execute() {
		$plugin =& $this->_plugin;
		$journal =& $this->_journal;

		$plugin->updateSetting($journal->getId(), 'maxArticlesCount', (int) $this->getData('maxArticlesCount'));
		$plugin->updateSetting($journal->getId(), 'months', (int) $this->getData('months'));
		$plugin->updateSetting($journal->getId(), 'mixOldStats', (boolean) $this->getData('mixOldStats'));
	}
}

?>
