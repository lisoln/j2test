<?php

/**
 * @file AboutHandler.inc.php
 *
 * Copyright (c) 2003-2007 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package pages.editor
 * @class AboutHandler
 *
 * Handle requests for editor functions. 
 *
 * $Id: AboutHandler.inc.php,v 1.43 2007/12/04 02:44:39 asmecher Exp $
 */

import('classes.handler.Handler');

class AboutHandler extends Handler {
	/**
	 * Setup common template variables.
	 * @param $subclass boolean set to true if caller is below this handler in the hierarchy
	 */
	function setupTemplate($subclass = false) {
		parent::setupTemplate();
		$templateMgr = &TemplateManager::getManager();
		$journal =& Request::getJournal();

		Locale::requireComponents(array(LOCALE_COMPONENT_OJS_MANAGER, LOCALE_COMPONENT_PKP_MANAGER));

		if (!$journal || !$journal->getSetting('restrictSiteAccess')) {
			$templateMgr->setCacheability(CACHEABILITY_PUBLIC);
		}
		if ($subclass) $templateMgr->assign('pageHierarchy', array(array(Request::url(null, 'about'), 'about.aboutTheJournal')));
	}

     /**
      * Display the reviewer index page
      */
     function reviewerIndex($args) {
		parent::validate(true);

		AboutHandler::setupTemplate(true);

		$journalSettingsDao = &DAORegistry::getDAO('JournalSettingsDAO');
		$journal = &Request::getJournal();

		$templateMgr = &TemplateManager::getManager();
		$plugin =& Registry::get('plugin');

          $Fields = array("`users`.`salutation`" => "plugins.generic.reviewerindex.salutation",
                                   "`users`.`first_name`" => "plugins.generic.reviewerindex.firstname",
                                   "`users`.`middle_name`" => "plugins.generic.reviewerindex.middlename",
                                   "`users`.`last_name`" => "plugins.generic.reviewerindex.lastname",
                                   "`users`.`initials`" => "plugins.generic.reviewerindex.initials",
                                   "`users`.`email`" => "plugins.generic.reviewerindex.email",
                                   "`users`.`country`" => "plugins.generic.reviewerindex.country",
                                   "COUNT(*) AS CN" => "plugins.generic.reviewerindex.review");
  		$fieldDBNames = array("`users`.`salutation`" => "salutation",
  		                                      "`users`.`first_name`" => "first_name",
  		                                      "`users`.`middle_name`" => "middle_name",
  		                                      "`users`.`last_name`" => "last_name",
  		                                      "`users`.`initials`" => "initials",
  		                                      "`users`.`email`" => "email",
  		                                      "`users`.`country`" => "country",
  		                                      "COUNT(*) AS CN" => "cn");

          $journalId = $journal->getJournalId();
          $selectedFields = $plugin->getSetting($journalId, 'selectedFields');
          $selectedOrderField = $plugin->getSetting($journalId, 'selectedOrderField');
          $order = $plugin->getSetting($journalId, 'order');
          $fromDate = $plugin->getSetting($journalId, 'fromDate');
          $toDate = $plugin->getSetting($journalId, 'toDate');
		if ( !isset($selectedFields) ) {
               $selectedFields = array("`users`.`first_name`",
                                                    "`users`.`middle_name`",
                                                    "`users`.`last_name`",
                                                    "`users`.`country`",
                                                    "COUNT(*) AS CN");
               $selectedOrderField = array("COUNT(*) AS CN", "`users`.`last_name`", "`users`.`first_name`", "", "", "", "", "", "", "");
               $order = array('DESC', 'ASC', 'ASC', 'ASC', 'ASC', 'ASC', 'ASC', 'ASC', 'ASC', 'ASC');
               $fromDate = date('Y-m-d', strtotime('last year'));
               $toDate = date('Y-m-d');
		}

          $selectedFieldNames = array();
          foreach ($selectedFields as $k) {
               $selectedFieldNames[] = $fieldDBNames[$k];
          }
          $templateMgr->assign_by_ref('Fields', $Fields);
          $templateMgr->assign_by_ref('selectedFields', $selectedFields);
          $templateMgr->assign_by_ref('selectedFieldNames', $selectedFieldNames);
          $templateMgr->assign('cols', count($selectedFieldNames));
          $queryFields = implode(", ", $selectedFields);
          if (!in_array("COUNT(*) AS CN", $selectedFields)) {
               array_push($selectedFields, "COUNT(*) AS CN");
               $queryFields = implode(", ", $selectedFields);
               array_pop($selectedFields);
          }
          $quertOrderArray = array();
          foreach ($selectedOrderField as $i => $o) {
               if ($o != "") {
                    if ($o == "COUNT(*) AS CN") $o = "CN";
                    $d = $order[$i];
                    $quertOrderArray[] = "$o $d";
               }
          }
          $quertOrder = "";
          if (count($quertOrderArray) > 0)
               $quertOrder = " ORDER BY " . implode(", ", $quertOrderArray);
          $query = "SELECT DISTINCT {$queryFields} FROM `users` LEFT JOIN (`review_assignments`, `articles`) ON (`users`.`user_id` = `review_assignments`.`reviewer_id` AND `articles`.`article_id` = `review_assignments`.`submission_id`) WHERE `articles`.`journal_id` = ? AND `review_assignments`.`date_completed` IS NOT NULL AND `review_assignments`.`declined` = 0 AND `review_assignments`.`cancelled` = 0 AND (`review_assignments`.`date_completed` BETWEEN ? AND ?) GROUP BY `review_assignments`.`reviewer_id`{$quertOrder}";
          //$dao = &DAORegistry::getDAO('DAO');
          import('lib.pkp.classes.db.DAO');
          $dao = &new DAO();
          $result = &$dao->retrieve($query, array(
               $journalId,
               "{$fromDate}",
               "{$toDate}"
          ));
          $users = array();

          while (!$result->EOF)
          {
               $row = $result->GetRowAssoc(false);
               $users[] = $row;
               $result->MoveNext();
          }
          $result->Close();
          unset($result);
          $templateMgr->assign_by_ref('users', $users);
          $templateMgr->assign('dateFrom', $fromDate);
          $templateMgr->assign('dateTo', $toDate);

          if (isset($args[0])) {
               $templateFile = 'printerFriendly.tpl';
          } else {
               $templateFile = 'reviewerIndex.tpl';
          }
		$templateMgr->display($plugin->getTemplatePath() . $templateFile);
     }

}

?>
