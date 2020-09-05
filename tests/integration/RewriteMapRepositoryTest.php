<?php
declare(strict_types=1);

namespace IntegerNet\RewriteMap;

use IntegerNet\RewriteMap\Model\RewriteMapRepository;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @magentoAppIsolation enabled
 * @magentoDbIsolation enabled
 */
class RewriteMapRepositoryTest extends TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;
    /**
     * @var RewriteMapRepository
     */
    private $rewriteMapRepository;

    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->rewriteMapRepository = $this->objectManager->get(RewriteMapRepository::class);
    }

    /**
     * @magentoDataFixture Magento/Store/_files/second_store.php
     * @test
     */
    public function loads_rewrite_map_based_on_magento_url_rewrites()
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

        $rewriteMap = $this->rewriteMapRepository->getByStoreAndType($defaultStoreId, 301);
        $this->assertEquals("/request-301-store-1 /target-301-store-1\n", $rewriteMap->getContent());

        $rewriteMap = $this->rewriteMapRepository->getByStoreAndType($defaultStoreId, 302);
        $this->assertEquals("/request-302-store-1 /target-302-store-1\n", $rewriteMap->getContent());

        $rewriteMap = $this->rewriteMapRepository->getByStoreAndType($anotherStoreId, 301);
        $this->assertEquals("/request-301-store-2 /target-301-store-2\n", $rewriteMap->getContent());

        $rewriteMap = $this->rewriteMapRepository->getByStoreAndType($anotherStoreId, 302);
        $this->assertEquals("/request-302-store-2 /target-302-store-2\n", $rewriteMap->getContent());
    }

    /**
     * @see /dev/tests/integration/testsuite/Magento/Store/_files/second_store.php
     * @return int
     */
    private function getSecondStoreIdFromFixtureFile(): int
    {
        return (int) $this->objectManager->get(StoreRepositoryInterface::class)->get('fixture_second_store')->getId();
    }
}
