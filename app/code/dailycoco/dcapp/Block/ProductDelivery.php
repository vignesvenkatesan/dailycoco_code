<?php
namespace Dailycoco\Dcapp\Block;

/**
 * 
 */
class ProductDelivery extends \Magento\Framework\View\Element\Template
{

	protected $request;

    protected $orderRepository;
	
	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\App\Request\Http $request,
		\Magento\Sales\Api\OrderRepositoryInterface $orderRepository
	)
	{
		$this->request = $request;
		$this->orderRepository = $orderRepository;
		parent::__construct($context);
	}

	public function sayHello()
	{
		$orderId = $this->request->getParam('order_id');
		$order = $this->orderRepository->get($orderId);

		return $order;
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
}
