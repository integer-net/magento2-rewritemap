<?php
/**
 * integer_net GmbH Magento Module
 *
 * @copyright  Copyright (c) 2020 integer_net GmbH (http://www.integer-net.de/)
 * @author     Bernard Delhez <bd@integer-net.de>
 */

namespace IntegerNet\RewriteMap\Model;

use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use Magento\UrlRewrite\Model\OptionProvider as UrlRewriteOptionProvider;

class RewriteMapsSavingService
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var RewriteMapRepository
     */
    private $urlRewriteRepository;

    public function __construct(
        Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        RewriteMapRepository $urlRewriteRepository
    ) {
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->urlRewriteRepository = $urlRewriteRepository;
    }

    /**
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function saveRewriteMapsForAllStores(): void
    {
        foreach ($this->storeManager->getStores() as $store) {
            $storeId = (int)$store->getId();
            $this->saveRewriteMap(
                $this->urlRewriteRepository->getByStoreAndType($storeId, UrlRewriteOptionProvider::PERMANENT)
            );
            $this->saveRewriteMap(
                $this->urlRewriteRepository->getByStoreAndType($storeId, UrlRewriteOptionProvider::TEMPORARY)
            );
        }
    }

    /**
     * @param RewriteMap $rewriteMap
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function saveRewriteMap(RewriteMap $rewriteMap): void
    {
        $varDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $varDirectory->writeFile(
            'rewrite_map' . DIRECTORY_SEPARATOR . $rewriteMap->getFilename(),
            $rewriteMap->getContent(),
            'w'
        );
    }
}
