<?php
namespace Dailycoco\Dcapp\Block;
use Magento\Customer\Model\Session;

/**
 * 
 */
class DailycocoHeaderNavigation extends \Magento\Framework\View\Element\Template
{

	protected $customerSession;

	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
	\Magento\Customer\Model\Session $customerSession)
	{
		$this->_isScopePrivate = true;
		$this->customerSession = $customerSession;
		parent::__construct($context);
	}

	public function getCustomerId()
	{
		return $this->customerSession->getCustomer()->getId();
	}
}