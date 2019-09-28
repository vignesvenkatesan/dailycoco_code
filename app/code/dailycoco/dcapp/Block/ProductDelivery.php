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

    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    protected $deliveryDate;
	
	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\App\Request\Http $request,
		\Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
		ProductDeliveryStorage $productDelivery,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Framework\Data\Form\FormKey $formKey
	)
	{
		$this->request = $request;
		$this->orderRepository = $orderRepository;
		$this->productDelivery = $productDelivery;
		$this->customerSession = $customerSession;
		$this->formKey = $formKey;

		parent::__construct($context);
	}

	/**
     * @return \Magento\Framework\Data\Form\FormKey
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
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

	public function getOrderDeliveryForToday()
	{
		$deliveryDetails = $this->productDelivery->getOrderDeliveryListForToday();
		return $deliveryDetails;
	}

	public function getOrderDeliveryForDate()
	{
		$searchddate = "";
    	try{
    		$post = $this->request->getPost();
    		$searchddate = $post['searchddate'];
    	}
    	catch(Exception $e){
    		$searchddate = "";
    	}
    	$this->deliveryDate = $searchddate;

		$deliveryDetails = $this->productDelivery->getOrderDeliveryListForDate($searchddate);
		return $deliveryDetails;
	}

	public function getOrderDeliveryDate()
	{
		return $this->deliveryDate;
	}
	
}
