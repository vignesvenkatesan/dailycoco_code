<?php
namespace Dailycoco\Dcapp\Block;

use Dailycoco\Dcapp\Model\ProductDelivery\ProductDeliveryStorage;

/**
 * 
 */
class ProductDelivery extends \Magento\Framework\View\Element\Template
{

	protected $request;

    protected $orderRepository;

    protected $productDelivery;

    protected $customerSession;
	
	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\App\Request\Http $request,
		\Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
		ProductDeliveryStorage $productDelivery,
		\Magento\Customer\Model\Session $customerSession
	)
	{
		$this->request = $request;
		$this->orderRepository = $orderRepository;
		$this->productDelivery = $productDelivery;
		$this->customerSession = $customerSession;

		parent::__construct($context);
	}

	public function getOrderDeliveryDetails()
	{
		$orderId = $this->request->getParam('order_id');
		$deliveryDetails = $this->productDelivery->getProducDeliveryByOrderId($orderId);


		return $deliveryDetails;
	}

	public function deliveryStartDate()
	{
		$orderDate = $this->convertOrderDate();
		$orderHour = date('H',strtotime($orderDate));

		if($orderHour <= 23)
			return date("d-M-Y",strtotime('+1 day',strtotime($orderDate)));
		else
			return date("d-M-Y",strtotime('+2 day',strtotime($orderDate)));
	}

	public function convertOrderDate()
	{
		$orderId = $this->request->getParam('order_id');
		return date('Y-m-d H:i:s',
			strtotime('+5 hour +30 minutes',strtotime($this->orderRepository->get($orderId)->getCreatedAt()))
		);
	}

	public function convertGivenDate($date)
	{
		return date('d-M-Y',strtotime($date));
	}

	public function getCustomerId()
	{
		return $this->customerSession->getCustomer()->getId();
	}

	public function getAllOrdersByCustId()
	{
		$customerId = $this->getCustomerId();
		$deliveryDetails = $this->productDelivery->getProducDeliveryByCustId($customerId);

		return $deliveryDetails;
	}
}
