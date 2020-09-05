<?php
/**
 * integer_net GmbH Magento Module
 *
 * @copyright  Copyright (c) 2020 integer_net GmbH (http://www.integer-net.de/)
 * @author     Bernard Delhez <bd@integer-net.de>
 */

namespace IntegerNet\RewriteMap\Model;

class RewriteMap
{
    /** @var string */
    private $rewriteMapFileContent;
    /**
     * @var int
     */
    private $storeId;
    /**
     * @var int
     */
    private $redirectType;

    public function __construct(int $storeId, int $redirectType)
    {
        $this->rewriteMapFileContent = '';
        $this->storeId = $storeId;
        $this->redirectType = $redirectType;
    }

    public function addRewrite(string $request, string $target)
    {
        $this->rewriteMapFileContent .= sprintf("/%s /%s\n", $request, $target);
    }

    public function getFilename(): string
    {
        return sprintf('rewrite-map-%s-store-%s.txt', $this->redirectType, $this->storeId);
    }

    public function getContent(): string
    {
        return $this->rewriteMapFileContent;
    }
}
