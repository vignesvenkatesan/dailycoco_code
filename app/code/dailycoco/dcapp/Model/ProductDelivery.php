<?php
namespace Dailycoco\Dcapp\Model;

class ProductDelivery extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'dailycoco_Dcapp_product_delivery';

	protected $_cacheTag = 'dailycoco_Dcapp_product_delivery';

	protected $_eventPrefix = 'dailycoco_Dcapp_product_delivery';

	protected function _construct()
	{
		$this->_init('Dailycoco\Dcapp\Model\ResourceModel\ProductDelivery');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}