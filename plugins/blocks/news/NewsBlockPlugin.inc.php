<?php

/**
 * @file NewsBlockPlugin.inc.php
 *
 * Copyright (c) 2012 Projecte Ictineo (www.projecteictineo.com)
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class NewsBlockPlugin
 * @ingroup plugins_blocks_news
 *
 * @brief Class for news block plugin
 *
 */

// $Id$


import('lib.pkp.classes.plugins.BlockPlugin');

class NewsBlockPlugin extends BlockPlugin {
	/**
	 * Install default settings on journal creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return Locale::translate('plugins.block.news.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return Locale::translate('plugins.block.news.description');
	}

	/**
	 * Get the HTML contents for this block.
	 * @param $templateMgr object
	 * @return $string
	 */
    function getContents(&$templateMgr) {
        // Make sure we're within a Journal context
        $journal =& Request::getJournal();
        if (!$journal) return false;

        // Make sure announcements and plugin are enabled
        //$announcementsEnabled = $journal->getSetting('enableAnnouncements');
        $announcementBlockPlugin =& $this;
        //if (!$announcementsEnabled || !$announcementBlockPlugin->getEnabled()) return false;

        // Make sure the block type is specified and valid
        /*		
        $type = array_shift($args);
        $typeMap = array(
	        'rss' => 'rss.tpl',
	        'rss2' => 'rss2.tpl',
	        'atom' => 'atom.tpl'
        );
        $mimeTypeMap = array(
	        'rss' => 'application/rdf+xml',
	        'rss2' => 'application/rss+xml',
	        'atom' => 'application/atom+xml'
        );
        if (!isset($typeMap[$type])) return false;
        */

        // Get limit setting, if any 
        // $limitRecentItems = $announcementBlockPlugin->getSetting($journal->getId(), 'limitRecentItems');
        // $recentItems = (int) $announcementBlockPlugin->getSetting($journal->getId(), 'recentItems');

        $limitRecentItems = true;
        $recentItems = 5;

        $announcementDao =& DAORegistry::getDAO('AnnouncementDAO');
        $journalId = $journal->getId();
        if ($limitRecentItems && $recentItems > 0) {
	        import('lib.pkp.classes.db.DBResultRange');
	        $rangeInfo = new DBResultRange($recentItems, 1);
	        $announcements =& $announcementDao->getAnnouncementsNotExpiredByAssocId(ASSOC_TYPE_JOURNAL, $journalId, $rangeInfo);
        } else {
	        $announcements =& $announcementDao->getAnnouncementsNotExpiredByAssocId(ASSOC_TYPE_JOURNAL, $journalId);
        }

        // Get date of most recent announcement
        // $lastDateUpdated = $announcementBlockPlugin->getSetting($journal->getId(), 'dateUpdated');
        $lastDateUpdated = '';
        if ($announcements->wasEmpty()) {
	        if (empty($lastDateUpdated)) { 
		        $dateUpdated = Core::getCurrentDate(); 
		        $announcementBlockPlugin->updateSetting($journal->getId(), 'dateUpdated', $dateUpdated, 'string');			
	        } else {
		        $dateUpdated = $lastDateUpdated;
	        }
        } else {
	        $mostRecentAnnouncement =& $announcementDao->getMostRecentAnnouncementByAssocId(ASSOC_TYPE_JOURNAL, $journalId);
	        $dateUpdated = $mostRecentAnnouncement->getDatetimePosted();
	        if (empty($lastDateUpdated) || (strtotime($dateUpdated) > strtotime($lastDateUpdated))) { 
		        $announcementBlockPlugin->updateSetting($journal->getId(), 'dateUpdated', $dateUpdated, 'string');			
            }
        }

        $versionDao =& DAORegistry::getDAO('VersionDAO');
        $version =& $versionDao->getCurrentVersion();

        $templateMgr =& TemplateManager::getManager();
        $templateMgr->assign('ojsVersion', $version->getVersionString());
        $templateMgr->assign('selfUrl', Request::getCompleteUrl()); 
        $templateMgr->assign('dateUpdated', $dateUpdated);
        $templateMgr->assign_by_ref('news', $announcements->toArray());
        $templateMgr->assign_by_ref('journal', $journal);

        return parent::getContents($templateMgr);
    }
}
?>
