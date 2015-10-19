<?php

/**
 * @defgroup plugins_blocks_popularArtilces
 */
 
/**
 * @file plugins/blocks/popularArticles/index.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_blocks_popularArtilces
 * @brief Wrapper for PopularArticlesBlockPlugin block plugin.
 *
 */


require_once('PopularArticlesBlockPlugin.inc.php');

return new PopularArticlesBlockPlugin();

?>
