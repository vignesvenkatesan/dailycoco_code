<?php
namespace Dailycoco\Dcapp\Model\ResourceModel\ProductDelivery;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'order_id';
	protected $_eventPrefix = 'dailycoco_Dcapp_product_delivery';
	protected $_eventObject = 'product_delivery_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Dailycoco\Dcapp\Model\ProductDelivery', 'Dailycoco\Dcapp\Model\ResourceModel\ProductDelivery');
	}

}