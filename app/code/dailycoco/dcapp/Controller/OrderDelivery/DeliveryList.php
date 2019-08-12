<?php
namespace Dailycoco\Dcapp\Controller\OrderDelivery;


class DeliveryList extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	protected $productDeliveryFactory;

	protected $_customerSession;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Customer\Model\Session $customerSession,
		\Dailycoco\Dcapp\Model\ProductDeliveryFactory $productDeliveryFactory
		)
	{
		$this->_pageFactory = $pageFactory;
		$this->_customerSession = $customerSession;
		$this->productDeliveryFactory = $productDeliveryFactory;
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