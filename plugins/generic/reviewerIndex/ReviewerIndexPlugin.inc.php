<?php
/**
 * @file ReviewerIndexPlugin.inc.php
 *
 * Copyright (c) 2009 <Mahmoud Saghaei>
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ReviewerIndexPlugin
 * @ingroup plugins_generic_reviewerindex
 *
 * @brief Produces a list of reviewers who have completed a review
 */
#import('lib.pkp.classes.plugins.GenericPlugin');
import('classes.plugins.GenericPlugin');

class ReviewerIndexPlugin extends GenericPlugin
{

    function getName()
    {

        return 'ReviewerIndexPlugin';
    }

    function getDisplayName()
    {

        return Locale::translate('plugins.generic.reviewerindex.displayName');
    }

    function getDescription()
    {

        return Locale::translate('plugins.generic.reviewerindex.description');
    }
    /**
     * Called as a plugin is registered to the registry
     * @param @category String Name of category plugin was registered to
     * @return boolean True iff plugin initialized successfully; if false,
     *         the plugin will not be registered.
     */

    function register($category, $path)
    {

        if (parent::register($category, $path))
        {

            if ($this->getEnabled())
            {
				HookRegistry::register('Templates::About::Index::Other',
					array(&$this, 'callback'));
				HookRegistry::register('LoadHandler', array(&$this, 'handleRequest'));
            }
            $this->addLocaleData();

            return true;
        }

        return false;
    }

	function callback($hookName, $args) {
		$params =& $args[0];
		$smarty =& $args[1];
		$output =& $args[2];
		$url = Request::url(null, 'about', 'reviewerIndex');
		$output = "<li>&#187; <a href='{$url}'>" . Locale::translate("plugins.generic.reviewerindex.linklabel") . "</a></li>";
	}

	function handleRequest($hookName, $args) {
		$page =& $args[0];
		$op =& $args[1];
		$sourceFile =& $args[2];

		$op_arr = array('publicFolder', 'publicFileUpload', 'publicFileMakeDir', 'publicFileDelete');
		if ($page === 'about' && $op == 'reviewerIndex') {
			$this->import('AboutHandler');
			Registry::set('plugin', $this);
			define('HANDLER_CLASS', 'AboutHandler');
		}

	}

    /**
     * Determine whether or not this plugin is enabled.
     */

    function getEnabled()
    {
        $journal = &Request::getJournal();

        if (!$journal)
        return false;

        return $this->getSetting($journal->getJournalId() , 'enabled');
    }
    /**
     * Set the enabled/disabled state of this plugin
     */

    function setEnabled($enabled)
    {
        $journal = &Request::getJournal();

        if ($journal)
        {
            $this->updateSetting($journal->getJournalId() , 'enabled', $enabled ? true : false);

            return true;
        }

        return false;
    }
    /**
     * Display verbs for the management interface.
     */

    function getManagementVerbs()
    {
        $verbs = array();

        if ($this->getEnabled())
        {
            $verbs[] = array(
                'disable',
                Locale::translate('manager.plugins.disable')
            );
            $verbs[] = array(
                'settings',
                Locale::translate('plugins.generic.reviewerindex.settings')
            );
        }
        else
        {
            $verbs[] = array(
                'enable',
                Locale::translate('manager.plugins.enable')
            );
        }

        return $verbs;
    }
    /**
     * Perform management functions
     */

    function manage($verb, $args)
    {
        $returner = true;
        $templateMgr = &TemplateManager::getManager();
        $templateMgr->register_function('plugin_url', array(
            &$this,
            'smartyPluginUrl'
        ));
        $pageCrumbs = array(
            array(
                Request::url(null, 'user') ,
                'navigation.user'
            ) ,
            array(
                Request::url(null, 'manager') ,
                'user.role.manager'
            )
        );
        $journal = &Request::getJournal();

        switch ($verb)
        {
        case 'enable':
            $this->setEnabled(true);
        break;
        case 'disable':
            $this->setEnabled(false);
        break;
        case 'settings':
          $templateMgr->assign('pageHierarchy', $pageCrumbs);
          $pageCrumbs[] = array(
           Request::url(null, 'manager', 'plugins') ,
           Locale::translate('manager.plugins') ,
           true
          );
          $this->import('SettingsForm');
          $form = &new SettingsForm($this, $journal->getJournalId());
          $form->readInputData();
		if (!isset($form->_data['selectedFields']))
			$form->initData();
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
  		$orderFields = array_merge(array("" => ""), $Fields);
  		$orders = array("ASC" => Locale::translate('plugins.generic.reviewerindex.ascending'), "DESC" => Locale::translate('plugins.generic.reviewerindex.descending'));
          $selectedFields = $form->getData('selectedFields');
          $selectedOrderField = $form->getData('selectedOrderField');
          $order = $form->getData('order');
          $fromDate = $form->getData('fromDate');
          $toDate = $form->getData('toDate');
          $selectedFieldNames = array();
          foreach ($selectedFields as $k) {
          $selectedFieldNames[] = $fieldDBNames[$k];
          }
          $templateMgr->assign_by_ref('Fields', $Fields);
          $templateMgr->assign_by_ref('selectedFields', $selectedFields);
          $templateMgr->assign_by_ref('orderFields', $orderFields);
          $templateMgr->assign_by_ref('selectedOrderField', $selectedOrderField);
          $templateMgr->assign_by_ref('order', $order);
          $templateMgr->assign_by_ref('orders', $orders);
          $templateMgr->assign_by_ref('selectedFieldNames', $selectedFieldNames);
          $templateMgr->assign('cols', count($selectedFieldNames));
          $journal_id = $journal->getJournalId();
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
           $journal_id,
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
          $templateMgr->assign('hideTools', Request::getUserVar('hideTools'));
          $form->execute();
          $form->display();

          return true;
        }
        Request::redirect(null, 'manager', 'plugins');
        $returner = true;

        break;
    }
}
?>
