<?php
namespace Dailycoco\Dcapp\Model\ProductDelivery;

use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class ProductDeliveryStorage
{
	const TABLE_NAME = 'dailycoco_Dcapp_product_delivery';

	/**
     * Code of "Integrity constraint violation: 1062 Duplicate entry" error
     */
    const ERROR_CODE_DUPLICATE_ENTRY = 23000;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    /**
     * @var Resource
     */
    protected $resource;

    protected $logger;

    protected $orderRepository;


    /**
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource,
        LoggerInterface $logger,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->connection = $resource->getConnection();
        $this->resource = $resource;
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Insert multiple
     *
     * @param array $data
     * @return void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Exception
     */
    public function insertMultiple($tableName = self::TABLE_NAME, $data)
    {
        try {
            $tableName = $this->resource->getTableName(self::TABLE_NAME);
            return $this->connection->insertMultiple($tableName, $data);
        } catch (\Exception $e) {
            if ($e->getCode() === self::ERROR_CODE_DUPLICATE_ENTRY
                && preg_match('#SQLSTATE\[23000\]: [^:]+: 1062[^\d]#', $e->getMessage())
            ) {
                throw new \Magento\Framework\Exception\AlreadyExistsException(
                    __('URL key for specified store already exists.')
                );
            }
            throw $e;
        }
    }

    public function getProducDeliveryByOrderId($orderId)
    {
        $tableName = $this->resource->getTableName(self::TABLE_NAME);

        $sql = "SELECT * from ".self::TABLE_NAME." where order_id =".$orderId;
        return $this->connection->fetchAll($sql);
    }

    public function getProducDeliveryByCustId($customerId)
    {
        $tableName = $this->resource->getTableName(self::TABLE_NAME);

        $sql = "SELECT dl.product_sku,sum(dl.quantity) as quantity,dl.delivery_status, dl.delivery_date from ".self::TABLE_NAME." as dl inner join sales_order as so on so.entity_id =  dl.order_id where so.customer_id =".$customerId." and dl.delivery_status = 'Yet to Start' group by dl.delivery_date";
        return $this->connection->fetchAll($sql);
    }


    public function addProductDelivery($orderId)
	{
		$orderItemsDeliveryDays = array();
		$order = $this->orderRepository->get($orderId);

		$i=0;
		foreach ($order->getAllItems() as $item) {
			$productName = $item->getName();
			
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
   			$product = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getId());
   			$prdSKU = str_replace(" ", '', $productName);
            if(strstr($prdSKU, "-") >= 1){
                $arrSKU = explode("-", $prdSKU);
                $prdSKU = $arrSKU[0];
            }
   			$productDays = $this->getDaysFromProductName($productName,$item->getSku());

   			$prdMaxDays[$prdSKU] = 0;
   			if($prdMaxDays[$prdSKU] < $productDays){
   				$prdMaxDays[$prdSKU] = $productDays;
   			}
   			$orderItemsDeliveryDays[$prdSKU][$i]['days'] = $productDays;
   			$orderItemsDeliveryDays[$prdSKU][$i]['sku'] = $item->getSku();
   			$orderItemsDeliveryDays[$prdSKU][$i]['quantity'] = $item->getQtyOrdered();
   			
   			$i++;
		}

		$orderDate = date('Y-m-d H:i:s',
			strtotime('+5 hour +30 minutes',strtotime($order->getCreatedAt()))
		);

		$deliveryStartdate = date("Y-m-d",strtotime('+1 day',strtotime($orderDate)));

        $insertData = array();
		foreach ($orderItemsDeliveryDays as $key => $arrProductDays) {
			# code...(14 days and 5 days and 7 days)
			$deliveryDayCnt = 1;
            $deliveryStartdate = date("Y-m-d",strtotime('+1 day',strtotime($orderDate)));

			while($deliveryDayCnt <= $prdMaxDays[$key]){
				$dayQuantity = 0;
				$productSku = "";
				foreach($arrProductDays as $product){
					if($deliveryDayCnt <= $product['days'])
						$dayQuantity += $product['quantity'];
					$productSku = $product['sku'];
				}

                if(!array_key_exists($deliveryDayCnt,$insertData)) {
                    //generate order data
                    $insertData[$deliveryDayCnt]= [ 'order_id' => $orderId,
                                                    'delivery_date'=> $deliveryStartdate,
                                                    'quantity' => $dayQuantity,
                                                    'product_sku' => $productSku,
                                                    'delivery_status' => 'Yet to Start',
                    ];
                }else{
                    $insertData[$deliveryDayCnt]['quantity'] = $insertData[$deliveryDayCnt]['quantity'] + $dayQuantity;
                }
				

				$deliveryStartdate = date("Y-m-d",strtotime('+1 day',strtotime($deliveryStartdate)));
				$deliveryDayCnt++;
			}
		}

		//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		//$productDeliveryStorage = $objectManager->get('Dailycoco\Dcapp\Model\ProductDelivery\ProductDeliveryStorage');
		$this->insertMultiple('dailycoco_Dcapp_product_delivery', $insertData);
		
        return true;
	}

	function getDaysFromProductName($productName,$prdSKU){
        if(strstr($prdSKU, "-") >= 1){
            $arrSKU = explode("-", $prdSKU);
            $prdSKU = $arrSKU[0];
        }
        
		$strDays = str_replace($prdSKU, '', $productName);

		if($strDays == ""){
			return 1;
		}else{
			$noofDays = 0;
			$noofDays = str_replace(" Days", '', $strDays);
			return $noofDays;
		}

		return 0;
	}
}