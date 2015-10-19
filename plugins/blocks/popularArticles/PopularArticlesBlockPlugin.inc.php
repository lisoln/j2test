<?php

/**
 * @file plugins/blocks/popularArticles/PopularArticlesBlockPlugin.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PopularArticlesBlockPlugin
 * @ingroup plugins_blocks_popularArticles
 *
 * @brief Class for Popular Articles Block plugin
 */


import('lib.pkp.classes.plugins.BlockPlugin');

define('POPULAR_ARTICLES_BLOCK_PLUGIN_DEFAULT_MAX_ARTICLES_COUNT', 10);

class PopularArticlesBlockPlugin extends BlockPlugin {
	
	/**
	 * @copydoc LazyLoadPlugin::register() 
	 */
	function register($category, $path) {
		$success = parent::register($category, $path);
		if ($success) {
			$this->addLocaleData();
		}
		return $success;
	}

	/**
	 * @copydoc BlockPlugin::getSupportedContexts()
	 */
	function getSupportedContexts() {
		return array(BLOCK_CONTEXT_LEFT_SIDEBAR, BLOCK_CONTEXT_RIGHT_SIDEBAR, BLOCK_CONTEXT_HOMEPAGE);
	}

	/**
	 * @copydoc PKPPlugin::getName()
	 */
	function getName() {
		return 'popularArticlesBlockPlugin';
	}

	/**
	 * @copydoc PKPPlugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.block.PopularArticles.displayName');
	}

	/**
	 * @copydoc PKPPlugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.block.PopularArticles.description');
	}

	/**
         * @copydoc PKPPlugin::getTemplatePath()
         */
	function getTemplatePath() {
		return parent::getTemplatePath() . 'templates/';
	}

	/** 
	 * @copydoc PKPPlugin::getManagementVerbs()
	 */
	function getManagementVerbs() {
		$verbs = parent::getManagementVerbs();
		if ($this->getEnabled()) {
			$verbs[] = array('settings', __('manager.plugins.settings'));
		}
		return $verbs;
	}

	/**
	 * @copydoc PKPPlugin::manage()
	 */
	function manage($verb, $args, &$message, &$messageParams) {
		$returner = parent::manage($verb, $args, $message, $messageParams);
		$journal = $this->_getCurrentJournal();

		switch($verb) {
			case 'settings':
				$settingsForm = $this->_getSettingsForm($request, $journal);
				$settingsForm->initData();
				$settingsForm->display($request);
				break;
			case 'save':
				$settingsForm = $this->_getSettingsForm($request, $journal);
				$settingsForm->readInputData();
				if ($settingsForm->validate()) {
					$settingsForm->execute();
					$message = NOTIFICATION_TYPE_SUCCESS;
					$messageParams = array('contents' => __('plugins.blocks.popularArticles.settings.saved'));
					return false;
				} else {
					$settingsForm->display($request);
				}
				break;
			default:
				return $returner;
		}
		return true;
	}

	/**
	 * @copydoc BlockPlugin::getContents()
	 */
	function getContents(&$templateMgr) {
		$journal =& $this->_getCurrentJournal();
		if (!$journal) return '';

		$popularArticles = $this->_getPopularArticles($journal);
		if (empty($popularArticles)) return '';

		$templateMgr->assign('popularArticles', $popularArticles);
		$templateMgr->addStyleSheet(Request::getBaseUrl() . DIRECTORY_SEPARATOR . $this->getPluginPath() . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'popularArticles.css');
		return parent::getContents($templateMgr);
	}

	
	//
	// Private helper methods.
	//
	/**
	 * Get current requested journal.
	 * @return Journal
	 */
	function &_getCurrentJournal() {
		$application =& PKPApplication::getApplication();
		$request =& $application->getRequest();
		$router =& $request->getRouter();
		return $router->getContext($request);
	}

	/**
	 * Get plugin's setting form.
	 * @param $request Request
	 * @param $journal Journal
	 * @return PopularArticlesSettingsForm
	 */
	function _getSettingsForm(&$request, &$journal) {
		$this->import('form.PopularArticlesSettingsForm');
		$templateMgr =& TemplateManager::getManager($request);
		$templateMgr->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));
		return new PopularArticlesSettingsForm($this, $journal);
	}
	
	/*
	 * Gets the most popular articles in the last months.
	 * If months setting is null, get from all time.
	 * @param $journal Journal
	 * @return array
	 */
	function _getPopularArticles($journal) {
		$publishedArticleDao = DAORegistry::getDAO('PublishedArticleDAO');
		$journalId = $journal->getId();
		$maxArticlesCount = $this->getSetting($journalId, 'maxArticlesCount');
		if (!$maxArticlesCount) {
			$maxArticlesCount = POPULAR_ARTICLES_BLOCK_PLUGIN_DEFAULT_MAX_ARTICLES_COUNT;
		}

		$months = $this->getSetting($journalId, 'months');
		$mixOldStats = $this->getSetting($journalId, 'mixOldStats');

		import('lib.pkp.classes.db.DBResultRange');
		$dbResultRange = new DBResultRange($maxArticlesCount);
		$metricsDao =& DAORegistry::getDAO('MetricsDAO'); /* @var $metricsDao MetricsDAO */

		// Get view statistics.
		PluginRegistry::loadCategory('reports');
		$metricType = array($journal->getDefaultMetricType());
		if ($mixOldStats) {
			$metricType[] = OJS_METRIC_TYPE_LEGACY_DEFAULT;
		}

		$stats = $metricsDao->getMetrics(
			$metricType,
			array(STATISTICS_DIMENSION_SUBMISSION_ID),
			array(STATISTICS_DIMENSION_CONTEXT_ID => $journalId, STATISTICS_DIMENSION_ASSOC_TYPE => ASSOC_TYPE_ARTICLE),
			array(STATISTICS_METRIC => STATISTICS_ORDER_DESC),
			$dbResultRange,
			false 
		);

		$articles = array();
		
		if (!is_array($stats)) return $articles;

		foreach ($stats as $statRecord) {
			if (count($articlesInfo) == $maxArticlesCount) break;

			$articleId = $statRecord[STATISTICS_DIMENSION_SUBMISSION_ID];
			$count = $statRecord[STATISTICS_METRIC];

			$article = $publishedArticleDao->getPublishedArticleByArticleId($articleId); /* @var $article PublishedArticle */

			if (!is_a($article, 'PublishedArticle')) continue;

			if ($months) {
				$limit = strtotime('-' . $months . ' months');
				if (strtotime($article->getDatePublished()) < $limit) continue;
			}

			$articles[$articleId] = $article;
		}

		return $articles;
	}
}

?>
