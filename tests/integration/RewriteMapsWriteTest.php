<?php

namespace IntegerNet\RewriteMap;

use IntegerNet\RewriteMap\Model\RewriteMapRepository;
use IntegerNet\RewriteMap\Model\RewriteMapsSavingService;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\UrlRewrite\Model\OptionProvider;
use PHPUnit\Framework\TestCase;

/**
 * @magentoAppIsolation enabled
 * @magentoDbIsolation enabled
 */
class RewriteMapsWriteTest extends TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;
    /**
     * @var RewriteMapsSavingService
     */
    private $rewriteMapsSavingService;
    /**
     * @var Filesystem
     */
    private $magentoFileSystem;

    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->rewriteMapsSavingService = $this->objectManager->get(RewriteMapsSavingService::class);
        $this->magentoFileSystem = $this->objectManager->get(Filesystem::class);
    }

    protected function tearDown(): void
    {
        $this->removeRewriteMapDir();
    }

    /**
     * @magentoDataFixture Magento/Store/_files/second_store.php
     * @test
     */
    public function saves_rewrite_maps_to_var_directory()
    {
        $defaultStoreId = 1;
        $anotherStoreId = $this->getSecondStoreIdFromFixtureFile();
        (new UrlRewriteFactory())->createMagentoUrlRewrites(
            [
                [$defaultStoreId, 301, 'request-301-store-1', 'target-301-store-1'],
                [$defaultStoreId, 302, 'request-302-store-1', 'target-302-store-1'],
                [$anotherStoreId, 301, 'request-301-store-2', 'target-301-store-2'],
                [$anotherStoreId, 302, 'request-302-store-2', 'target-302-store-2'],
            ]
        );

        $this->rewriteMapsSavingService->saveRewriteMapsForAllStores();

        $this->assertFilesExistInVar(
            [
                "rewrite_map/rewrite-map-301-store-$defaultStoreId.txt",
                "rewrite_map/rewrite-map-302-store-$defaultStoreId.txt",
                "rewrite_map/rewrite-map-301-store-$anotherStoreId.txt",
                "rewrite_map/rewrite-map-302-store-$anotherStoreId.txt",
            ]
        );
    }

    /**
     * @return int
     * @see /dev/tests/integration/testsuite/Magento/Store/_files/second_store.php
     */
    private function getSecondStoreIdFromFixtureFile(): int
    {
        return (int)$this->objectManager->get(StoreRepositoryInterface::class)->get('fixture_second_store')->getId();
    }

    private function removeRewriteMapDir()
    {
        $varDirectory = $this->magentoFileSystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $varDirectory->delete('rewrite_map');
    }

    private function assertFilesExistInVar(array $filenames)
    {
        $varDirectory = $this->magentoFileSystem->getDirectoryRead(DirectoryList::VAR_DIR);
        $this->assertEquals(
            $filenames,
            array_filter($filenames, [$varDirectory, 'isFile']),
            'Files should be created in var'
        );
    }
}
