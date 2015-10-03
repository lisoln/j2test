<?php

/**
 * @defgroup plugins_generic_reviewerindex
 */
 
/**
 * @file plugins/generic/reviewerIndex/index.php
 *
 * Copyright (c) 2009 <Mahmoud Saghaei>
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_reviewerIndex
 * @brief Produces a list of reviewers who have completed a review
 *
 */

require_once('ReviewerIndexPlugin.inc.php');

return new ReviewerIndexPlugin();

?>
