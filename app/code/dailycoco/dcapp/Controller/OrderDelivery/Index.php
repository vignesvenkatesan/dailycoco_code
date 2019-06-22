<?php
namespace Dailycoco\Dcapp\Controller\OrderDelivery;


class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	protected $_customerSession;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Customer\Model\Session $customerSession
		)
	{
		$this->_pageFactory = $pageFactory;
		$this->_customerSession = $customerSession;
		return parent::__construct($context);
	}

	public function execute()
	{

		$resultPage = $this->_pageFactory->create();

		$navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation');
		//var_dump($order->getAllItems());
		//exit;

		return $resultPage;
	}
}
