<?php
namespace Dailycoco\Dcapp\Controller\Aboutus;


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

		return $resultPage;
	}
}