<?php
declare(strict_types=1);

namespace IntegerNet\RewriteMap;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class UrlRewriteFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    public function __construct()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * @param array $rewrites [int storeId, int redirectType, string requestPath, string targetPath]
     * @throws \Magento\UrlRewrite\Model\Exception\UrlAlreadyExistsException
     */
    public function createMagentoUrlRewrites(array $rewrites): void
    {
        /** @var UrlPersistInterface $urlPersist */
        $urlPersist = $this->objectManager->get(UrlPersistInterface::class);
        $urlRewrites = [];
        foreach ($rewrites as $rewrite) {
            $urlRewrites[] = $this->objectManager->create(
                UrlRewrite::class,
                [
                    'data' => [
                        UrlRewrite::STORE_ID      => $rewrite[0],
                        UrlRewrite::ENTITY_TYPE   => 'custom',
                        UrlRewrite::REDIRECT_TYPE => $rewrite[1],
                        UrlRewrite::REQUEST_PATH  => $rewrite[2],
                        UrlRewrite::TARGET_PATH   => $rewrite[3],
                    ],
                ]
            );
        }
        $urlPersist->replace($urlRewrites);
    }

}
