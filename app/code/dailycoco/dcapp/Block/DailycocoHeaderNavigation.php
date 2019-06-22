<?php
namespace Dailycoco\Dcapp\Block;

/**
 * 
 */
class DailycocoHeaderNavigation extends \Magento\Framework\View\Element\Template
{

	protected $customerSession;

	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
	\Magento\Customer\Model\Session $customerSession)
	{
		$this->customerSession = $customerSession;
		parent::__construct($context);
	}

	public function getCustomerId()
	{
		return $this->customerSession->getCustomer()->getId();
	}
}