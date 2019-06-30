<?php
namespace Dailycoco\Dcapp\Block;

/**
 * 
 */
class DCProductList extends \Magento\Framework\View\Element\Template
{

    protected $productCollection;
	
	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection
	)
	{
		$this->productCollection = $productCollection;
		parent::__construct($context);
	}

	public function getStockProductList()
	{
		//$collection = $this->productCollection->setFlag('has_stock_status_filter', true)->load();
		$collection = $this->productCollection->create();
        $collection->addAttributeToSelect('*');
		return $collection;
	}
}