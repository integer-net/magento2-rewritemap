<?php

namespace IntegerNet\RewriteMap;

use IntegerNet\RewriteMap\Model\RewriteMap;
use PHPUnit\Framework\TestCase;

class RewriteMapTest extends TestCase
{
    /**
     * @test
     * @dataProvider data_rewrite_map_arguments
     * @param $storeId
     * @param $redirectType
     */
    public function filename_contains_store_id_and_redirect_type(int $storeId, int $redirectType): void
    {
        $rewriteMap = new RewriteMap($storeId, $redirectType);
        $this->assertEquals(
            "rewrite-map-$redirectType-store-$storeId.txt",
            $rewriteMap->getFilename(),
            'file name should contain store id and redirect type'
        );
    }

    /**
     * @test
     */
    public function content_after_adding_redirects(): void
    {
        $rewriteMap = new RewriteMap(1, 301);
        $rewriteMap->addRewrite('shiny-new-url', 'old-boring-cms-page.html');
        $rewriteMap->addRewrite('another-shiny-url', 'category/product.html');
        $this->assertEquals(
            <<<'TXT'
            /shiny-new-url /old-boring-cms-page.html
            /another-shiny-url /category/product.html

            TXT,
            $rewriteMap->getContent(),
            'content should have one line per rewrite, paths starting with /'
        );
    }

    public static function data_rewrite_map_arguments(): array
    {
        return [
            [
                'store_id'      => 1,
                'redirect_type' => 301,
            ],
            [
                'store_id'      => 1,
                'redirect_type' => 302,
            ],
            [
                'store_id'      => 2,
                'redirect_type' => 301,
            ],
            [
                'store_id'      => 0,
                'redirect_type' => 302,
            ],
        ];
    }
}
