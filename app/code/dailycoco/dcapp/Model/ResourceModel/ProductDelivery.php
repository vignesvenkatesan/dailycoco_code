<?php
namespace Dailycoco\Dcapp\Model\ResourceModel;


class ProductDelivery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('dailycoco_Dcapp_product_delivery', 'order_id');
	}
	
}