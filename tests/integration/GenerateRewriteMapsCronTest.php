<?php
declare(strict_types=1);

namespace IntegerNet\RewriteMap;

use IntegerNet\RewriteMap\Cron\GenerateRewriteMaps;
use IntegerNet\RewriteMap\Model\RewriteMapsSavingService;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @magentoAppIsolation enabled
 */
class GenerateRewriteMapsCronTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var RewriteMapsSavingService&MockObject
     */
    private $writeMock;
    /**
     * @var GenerateRewriteMaps
     */
    private $cron;

    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->writeMock = $this->createMock(RewriteMapsSavingService::class);
        $this->objectManager->addSharedInstance($this->writeMock, RewriteMapsSavingService::class);
        $this->cron = $this->objectManager->get(GenerateRewriteMaps::class);
    }

    /**
     * @magentoAppArea crontab
     * @magentoConfigFixture catalog/seo/rewrite_maps_generation_enabled 1
     * @test
     */
    public function runs_if_configured()
    {
        $this->writeMock->expects($this->once())->method('saveRewriteMapsForAllStores');
        $this->cron->execute();
    }

    /**
     * @magentoAppArea crontab
     * @magentoConfigFixture catalog/seo/rewrite_maps_generation_enabled 0
     * @test
     */
    public function does_not_run_if_not_configured()
    {
        $this->writeMock->expects($this->never())->method('saveRewriteMapsForAllStores');
        $this->cron->execute();
    }
}
