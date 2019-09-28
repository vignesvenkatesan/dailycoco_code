<?php
namespace Dailycoco\Dcapp\Block;

use Magento\Backend\Block\Widget\Form\Container;
use Dailycoco\Dcapp\Model\ProductDelivery\ProductDeliveryStorage;

/**
 * 
 */
class SearchFilter extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    protected $request;
    protected $productDelivery;

    public function __construct(
    	\Magento\Framework\View\Element\Template\Context $context,
    	\Magento\Framework\App\Request\Http $request,
    	\Magento\Framework\Data\Form\FormKey $formKey,
        ProductDeliveryStorage $productDelivery)
	{
		$this->request = $request;
		$this->formKey = $formKey;
        $this->productDelivery = $productDelivery;

		parent::__construct($context);
	}

	/**
     * @return \Magento\Framework\Data\Form\FormKey
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    public function getDataGiven()
    {
    	$searchddate = "";
        $dummy = "";
    	try{
    		$post = $this->request->getPost();

            if(isset($post['searchddate']))
    		  $searchddate = $post['searchddate'];

            if(isset($post['statusupdate']))
                $dummy = $this->updateOrderDelivery($post);
    	}
    	catch(Exception $e){
    		$searchddate = "";
    	}
    	return $searchddate;
    }

    public function updateOrderDelivery($post)
    {
        return $this->productDelivery->updateOrderDeliveryStatus($post);
    }
}