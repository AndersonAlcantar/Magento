<?php

namespace Vass\Migration\Controller\Adminhtml\LegalesSub;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    public $_helperData;

    /**
    * @var \Magento\UrlRewrite\Model\UrlRewriteFactory
    */
    protected $_urlRewriteFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory,
        \Vass\Migration\Helper\Data $helperData
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->_urlRewriteFactory = $urlRewriteFactory;
        $this->_helperData = $helperData;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $idEntity = $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Migration\Model\LegalesSubCategory::class)->load($idEntity);
            $item = $model->getData();
            if(empty($item) || $model->getOrder() < 1){
                $lastItem = $this->_objectManager->create(\Vass\Migration\Model\LegalesSubCategory::class)->getCollection()                        
                    ->addFieldToFilter('type_id', $data['type_id'])
                    ->addFieldToFilter('subcategory_parent_id', $data['subcategory_parent_id'])
                    ->setOrder("`order`",'DESC');
                $lastItem->getSelect()->limit(1);
                $lastItem = $lastItem->getData();
                $data['order'] = 1;
                if(!empty($lastItem)){
                    foreach($lastItem as $item){
                        $data['order'] =  $item['order'] + 1;   
                    }
                }           
            }
            $model->setData($data);
            try {
                $model->save();
                
                $this->dataPersistor->clear('vendor_module_migration');
                if(empty($idEntity))
                {

                    print_r($data);

                    $model_cat = $this->_objectManager->create(\Vass\Migration\Model\LegalesCategory::class)->load($data['type_id']);
                    $legalescategory = $model_cat->getData();

                    $url_parse_category = strtr(strtolower($this->_helperData->eliminar_acentos($legalescategory['name'] )), " ", "-");

                    /*** url create ***/

                    if($data['subcategory_parent_id'] == 0){
                        
                        $target = "legales/index/index/page/".$data['type_id']."/subpage/".$model->getId();
                       
                        $url_parse_subcat = strtr(strtolower($this->_helperData->eliminar_acentos($data['name'])), " ", "-");
                        $url_final = $url_parse_category.'/'.$url_parse_subcat;

                    }elseif($data['parent_id'] ==0){

                        $target = "legales/index/index/page/".$data['type_id']."/subpage/".$data['subcategory_parent_id']."/subpagesub/".$model->getId();
                        
                        $model_sub = $this->_objectManager->create(\Vass\Migration\Model\LegalesSubCategory::class)->load($data['subcategory_parent_id']);
                        $legalessubcategory = $model_sub->getData();

                        $url_parse_subcategory = strtr(strtolower($this->_helperData->eliminar_acentos($legalessubcategory['name'] )), " ", "-");

                        $url_parse_subsubcat = strtr(strtolower($this->_helperData->eliminar_acentos($data['name'])), " ", "-");

                        $url_final = $url_parse_category.'/'.$url_parse_subcategory.'/'.$url_parse_subsubcat;
                    }else{

                        $target = "legales/index/index/page/".$data['type_id']."/subpage/".$data['subcategory_parent_id']."/subpagesub/".$data['parent_id']."/subpagesubsub/".$model->getId();

                        $model_sub = $this->_objectManager->create(\Vass\Migration\Model\LegalesSubCategory::class)->load($data['subcategory_parent_id']);
                        $legalessubcategory = $model_sub->getData();

                        $url_parse_subcategory = strtr(strtolower($this->_helperData->eliminar_acentos($legalessubcategory['name'] )), " ", "-");

                        $model_parent_id = $this->_objectManager->create(\Vass\Migration\Model\LegalesSubCategory::class)->load($data['parent_id']);
                        $legalesmodel_parent_id = $model_parent_id->getData();

                        $url_parse_model_parent_id = strtr(strtolower($this->_helperData->eliminar_acentos($legalesmodel_parent_id['name'] )), " ", "-");

                        $url_parse_subsubcat = strtr(strtolower($this->_helperData->eliminar_acentos($data['name'])), " ", "-");

                        $url_final = $url_parse_category.'/'.$url_parse_subcategory.'/'.$url_parse_model_parent_id.'/'.$url_parse_subsubcat;

                    }

                    $urlRewriteModel = $this->_urlRewriteFactory->create();
                    /* set current store id */
                    $urlRewriteModel->setStoreId(1);
                    /* this url is not created by system so set as 0 */
                    $urlRewriteModel->setIsSystem(0);
                    /* unique identifier - set random unique value to id path */
                    $urlRewriteModel->setIdPath(rand(1, 100000));
                    /* set actual url path to target path field */
                    $urlRewriteModel->setTargetPath($target);
                    /* set requested path which you want to create */
                    $urlRewriteModel->setRequestPath("legales/".$url_final.".html");
                    /* set current store id */
                    $urlRewriteModel->save();

                    $this->messageManager->addSuccessMessage(__('Legales Created.'));
                }elseif(!empty($idEntity)){

                    $this->messageManager->addSuccessMessage(__('Edited Legales.'));
                }

                if ($this->getRequest()->getParam('back')) {
                  
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred while saving the menu item.'));
            }

            $this->dataPersistor->set('vendor_module_migration', $data);
            
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    
}