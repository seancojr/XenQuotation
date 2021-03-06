<?php
/**
 * Copyright 2011 Ben O'Neill
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Helper for quotation related pages.
 * Provides validation methods, amongst other things.
 */
class XenQuotation_ControllerHelper_Quote extends XenForo_ControllerHelper_Abstract
{
	/**
	 * The current browsing user.
	 *
	 * @var XenForo_Visitor
	 */
	protected $_visitor;

	/**
	 * Additional constructor setup behavior.
	 */
	protected function _constructSetup()
	{
		$this->_visitor = XenForo_Visitor::getInstance();
	}
	
	/**
	 */
	public function assertCanViewQuotes()
	{
		$permissions = $this->_visitor->getPermissions();
		
		if (!XenForo_Permission::hasPermission($permissions, 'quote', 'view'))
		{
			throw $this->_controller->getErrorOrNoPermissionResponseException('xenquote_no_permission_to_view_quotations');
		}
	}
	
	/**
	 */
	public function assertQuoteValidAndViewable($quoteId)
	{
		$quote = $this->getQuoteOrError($quoteId);
		
		$quoteModel = $this->_controller->getModelFromCache('XenQuotation_Model_Quote');
		
		if (!$quoteModel->canViewQuotation($quoteId, $errorPhraseKey))
		{
			if ($errorPhraseKey == '')
			{
				$errorPhraseKey = 'xenquote_no_permission_to_view_quotations';
			}
			throw $this->_controller->getErrorOrNoPermissionResponseException($errorPhraseKey);
		}
		
		return $quote;
	}
	
	/**
	 */
	public function getQuoteOrError($quoteId, array $fetchOptions = array())
	{
		$quote = $this->_controller->getModelFromCache('XenQuotation_Model_Quote')->getQuoteById($quoteId, $fetchOptions);
		if (!$quote)
		{
			throw $this->_controller->responseException(
				$this->_controller->responseError(new XenForo_Phrase('xenquote_requested_quotation_not_found'), 404)
			);
		}

		return $quote;
	}
}

?>