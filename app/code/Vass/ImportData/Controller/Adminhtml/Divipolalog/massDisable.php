<?php

namespace Vass\ImportData\Controller\Adminhtml\Divipolalog;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Vass\ImportData\Model\ResourceModel\Divipolalog\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;

class massDisable extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
         Filter $filter,
        CollectionFactory $collectionFactory,
        \Vass\ImportData\Model\DivipolaFactory $divipolaFactory
        )
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
        $this->_divipolaFactory = $divipolaFactory;
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        $divipolaModel = $this->_divipolaFactory->create();
        foreach ($collection as $item) {
            $item->setAction('Inactive');
            
            $divipolaCollection = $divipolaModel->getCollection()
		    ->addFieldToFilter('id_divipola_log', array('eq' => $item->getId()));
            foreach ($divipolaCollection as $itemDivipola) {
                $itemDivipola->setStatus(0);
                $itemDivipola->save();
                
            }

            $item->save();
        }

        $this->messageManager->addSuccess(__('A total of %1 item(s) have been desactived .', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

}