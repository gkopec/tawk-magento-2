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

namespace Tawk\Widget\Controller\Adminhtml\SaveWidget;

use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\App\Action\Context;
use Psr\Log\LoggerInterface;
use Tawk\Widget\Model\WidgetFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultJsonFactory;
    protected $logger;
    protected $modelWidgetFactory;
    protected $request;

    public function __construct(
        WidgetFactory $modelFactory,
        Context $context,
        JsonFactory $resultJsonFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
        $this->modelWidgetFactory = $modelFactory->create();
        $this->request = $this->getRequest();
    }

    public function execute()
    {
        $response = $this->resultJsonFactory->create();
        $response->setHeader('Content-type', 'application/json');

        $pageId = $this->request->getParam('pageId', null);
        $widgetId = $this->request->getParam('widgetId', null);
        $storeId = (int)$this->request->getParam('id');

        if (!$pageId || !$widgetId || !$storeId) {
            return $response->setData(['success' => false]);
        }

        $alwaysdisplay = (bool)$this->request->getParam('alwaysdisplay', false);
        $excludeurl = $this->request->getParam('excludeurl');
        $donotdisplay = (bool)$this->request->getParam('donotdisplay', false);
        $includeurl = $this->request->getParam('includeurl');
        $enableVisitorRecognition = (bool)$this->request->getParam('enableVisitorRecognition');

        $model = $this->modelWidgetFactory->loadByForStoreId($storeId);

        if ($pageId != '-1') {
            $model->setPageId($pageId);
        }

        if ($widgetId != '-1') {
            $model->setWidgetId($widgetId);
        }

        $model->setForStoreId($storeId);

        $model->setAlwaysDisplay($alwaysdisplay);
        $model->setExcludeUrl($excludeurl);

        $model->setDoNotDisplay($donotdisplay);
        $model->setIncludeUrl($includeurl);

        $model->setEnableVisitorRecognition($enableVisitorRecognition);

        $model->save();

        return $response->setData(['success' => true]);
    }
}
