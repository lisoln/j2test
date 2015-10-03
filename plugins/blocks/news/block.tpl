{**
 * block.tpl
 *
 * Copyright (c) 2012 - Projecte Ictineo (www.projecteictineo.com)
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site sidebar menu -- "News" block.
 *
 * $Id$
 *}
<div class="block" id="sidebarNews">
	<h2 class="blockTitle" id="news">{translate key="plugins.block.news.link"}</h2>
  <ul>
    {foreach from=$news item=new}
      <li class="newElement"><a href="{url page="new" op="view" path=$new->getId()}" cass="newTitle">
        <h3>{$new->getLocalizedTitleFull()|strip|escape:"html"}</h3>
        {if $new->getLocalizedDescription()}
          <!--div class="newDescription">{$new->getLocalizedDescription()|strip|escape:"html"}</div-->
        {/if}
      </a></li>
    {/foreach}
  </ul>

</div>	
