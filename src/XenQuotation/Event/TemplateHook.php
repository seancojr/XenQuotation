<?php

/**
 * Catches specific templates and alters the content.
 * Typically used for adding tabs to the search page.
 */
class XenQuotation_Event_TemplateHook
{
	/**
	 * Adds the Quotations tab to the search form.
	 */
	public static function listen($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
	{		
		if ($hookName == 'search_form_tabs')
		{
			$viewParams = array(
				'searchType' => $template->getParam('searchType')
			);
			
			// append the xenquote_search_form_tabs template
			$contents .= $template->create('xenquote_search_form_tabs', $viewParams)->render();
		}
		else if ($hookName == 'forum_list_sidebar' &&
				 XenForo_Application::get('options')->xenquoteRandomQuote)
		{
			
			$quote = array(
				'quote_id' => 1,
				'quotation' => 'quotation',
				'parsedQuotation' => 'quotation'
			);
			
			$viewParams = array('quote' => $quote);
			
			// add the random quote to the sidebar
			$contents .= $template->create('xenquote_sidebar_random_quote', $viewParams)->render();
		}
	}
}

?>