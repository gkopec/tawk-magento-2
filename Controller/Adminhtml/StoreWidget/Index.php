<?php
/**
 * Tawk.to
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@tawk.to so we can send you a copy immediately.
 *
 * @copyright   Copyright (c) 2016 Tawk.to
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Tawk\Widget\Controller\Adminhtml\StoreWidget;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Psr\Log\LoggerInterface;
use Tawk\Widget\Model\WidgetFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultJsonFactory;
    protected $logger;
    protected $modelWidgetFactory;
    protected $request;

    public function __construct(
        WidgetFactory $modelWidgetFactory,
        Context $context,
        JsonFactory $resultJsonFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
        $this->modelWidgetFactory = $modelWidgetFactory->create();
        $this->request = $this->getRequest();
    }

    public function execute()
    {
        $response = $this->resultJsonFactory->create();
        $response->setHeader('Content-type', 'application/json');

        $storeId = (int)$this->request->getParam('id');
        if (!$storeId) {
            return $response->setData(['success' => false]);
        }

        $model = $this->modelWidgetFactory->loadByForStoreId($storeId);

        if (!$model->hasId()) {
            $model = $this->modelWidgetFactory;
        }

        $pageId = $model->getPageId();
        $widgetId =  $model->getWidgetId();

        $alwaysdisplay = $model->getAlwaysDisplay();
        $excludeurl = $model->getExcludeUrl();

        $donotdisplay = $model->getDoNotDisplay();
        $includeurl = $model->getIncludeUrl();

        $enableVisitorRecognition = $model->getEnableVisitorRecognition();

        return $response->setData([
            'success' => true,
            'pageid' => $pageId,
            'widgetid' => $widgetId,
            'alwaysdisplay' => $alwaysdisplay,
            'excludeurl' => $excludeurl,
            'donotdisplay' => $donotdisplay,
            'includeurl' => $includeurl,
            'enableVisitorRecognition' => $enableVisitorRecognition
        ]);
    }
}
