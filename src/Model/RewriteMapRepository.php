<?php
/**
 * integer_net GmbH Magento Module
 *
 * @copyright  Copyright (c) 2020 integer_net GmbH (http://www.integer-net.de/)
 * @author     Bernard Delhez <bd@integer-net.de>
 */

namespace IntegerNet\RewriteMap\Model;

use IntegerNet\RewriteMap\Model\RewriteMap as RewriteMap;
use Magento\Framework\Exception\InputException as InputException;
use Magento\UrlRewrite\Model\OptionProvider as UrlRewriteOptionProvider;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection as UrlRewriteCollection;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory as UrlRewriteCollectionFactory;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite as UrlRewrite;

class RewriteMapRepository
{
    private const ALLOWED_REDIRECT_TYPES = [UrlRewriteOptionProvider::PERMANENT, UrlRewriteOptionProvider::TEMPORARY];
    /**
     * @var UrlRewriteCollectionFactory
     */
    private $rewriteCollectionFactory;

    public function __construct(UrlRewriteCollectionFactory $rewriteCollectionFactory)
    {
        $this->rewriteCollectionFactory = $rewriteCollectionFactory;
    }

    /**
     * @param int $storeId
     * @param int $redirectType
     * @return RewriteMap
     * @throws InputException
     */
    public function getByStoreAndType(int $storeId, int $redirectType): RewriteMap
    {
        if (!in_array($redirectType, self::ALLOWED_REDIRECT_TYPES)) {
            throw new InputException(
                __(
                    'The given redirect type "%1" is not a allowed type: %2',
                    $redirectType,
                    print_r(self::ALLOWED_REDIRECT_TYPES, true)
                )
            );
        }

        /** @var UrlRewriteCollection $rewriteCollection */
        $rewriteCollection = $this->rewriteCollectionFactory->create();
        $rewriteCollection->addStoreFilter($storeId);
        $rewriteCollection->addFieldToFilter(UrlRewrite::REDIRECT_TYPE, $redirectType);

        $rewriteMap = new RewriteMap($storeId, $redirectType);

        foreach ($rewriteCollection as $urlRewrite) {
            /** @var \Magento\UrlRewrite\Model\UrlRewrite $urlRewrite */
            $rewriteMap->addRewrite($urlRewrite->getRequestPath(), $urlRewrite->getTargetPath());
        }

        return $rewriteMap;
    }
}
