<?php
declare(strict_types=1);

namespace IntegerNet\RewriteMap\Cron;

use IntegerNet\RewriteMap\Model\RewriteMapsSavingService as RewriteMapsSavingService;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\FileSystemException as FileSystemException;
use Magento\Framework\Exception\InputException;
use Psr\Log\LoggerInterface as LoggerInterface;

class GenerateRewriteMaps
{
    /**
     * @var RewriteMapsSavingService
     */
    private $rewriteMapsSavingService;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        RewriteMapsSavingService $rewriteMapsSavingService,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig)
    {
        $this->rewriteMapsSavingService = $rewriteMapsSavingService;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        if (!$this->scopeConfig->getValue('catalog/seo/rewrite_maps_generation_enabled')) {
            return;
        }

        try {
            $this->rewriteMapsSavingService->saveRewriteMapsForAllStores();
        } catch (InputException | FileSystemException $exception) {
            $this->logger->error('There was an error while generating the rewrite maps', ['exception' => $exception]);
        }
    }
}
