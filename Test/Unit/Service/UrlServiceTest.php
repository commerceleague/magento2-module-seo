<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Service;

use CommerceLeague\Seo\Service\UrlRetriever\CategoryUrlRetriever;
use CommerceLeague\Seo\Service\UrlRetriever\PageUrlRetriever;
use CommerceLeague\Seo\Service\UrlRetriever\ProductUrlRetriever;
use CommerceLeague\Seo\Service\UrlService;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Store\Model\Store;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UrlServiceTest extends TestCase
{
    /**
     * @var MockObject|HttpRequest
     */
    protected $request;

    /**
     * @var MockObject|CategoryUrlRetriever
     */
    protected $categoryUrlRetriever;

    /**
     * @var MockObject|PageUrlRetriever
     */
    protected $pageUrlRetriever;

    /**
     * @var MockObject|ProductUrlRetriever
     */
    protected $productUrlRetriever;

    /**
     * @var MockObject|Store
     */
    protected $store;

    /**
     * @var UrlService
     */
    protected $urlService;

    protected function setUp()
    {
        $this->request = $this->createMock(HttpRequest::class);
        $this->categoryUrlRetriever = $this->createMock(CategoryUrlRetriever::class);
        $this->pageUrlRetriever = $this->createMock(PageUrlRetriever::class);
        $this->productUrlRetriever = $this->createMock(ProductUrlRetriever::class);
        $this->store = $this->createMock(Store::class);

        $this->urlService = new UrlService(
            $this->request,
            $this->categoryUrlRetriever,
            $this->pageUrlRetriever,
            $this->productUrlRetriever
        );
    }

    public function testGetUrlByStoreWithUnknownActionName()
    {
        $this->request->expects($this->once())
            ->method('getFullActionName')
            ->willReturn('unknown');

        $this->categoryUrlRetriever->expects($this->never())
            ->method('getUrl');

        $this->productUrlRetriever->expects($this->never())
            ->method('getUrl');

        $this->pageUrlRetriever->expects($this->never())
            ->method('getUrl');

        $this->store->expects($this->never())
            ->method('getBaseUrl');

        $this->assertNull(
            $this->urlService->getUrlByStore($this->store)
        );
    }

    public function testGetUrlByStoreWithCategory()
    {
        $categoryId = 123;
        $categoryUrl = 'http://example.com/category.html';

        $this->request->expects($this->once())
            ->method('getFullActionName')
            ->willReturn('catalog_category_view');

        $this->request->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn($categoryId);

        $this->categoryUrlRetriever->expects($this->once())
            ->method('getUrl')
            ->with($categoryId, $this->store)
            ->willReturn($categoryUrl);

        $this->assertEquals(
            $categoryUrl,
            $this->urlService->getUrlByStore($this->store)
        );
    }

    public function testGetUrlByStoreWithProduct()
    {
        $productId = 123;
        $productUrl = 'http://example.com/product.html';

        $this->request->expects($this->once())
            ->method('getFullActionName')
            ->willReturn('catalog_product_view');

        $this->request->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn($productId);

        $this->productUrlRetriever->expects($this->once())
            ->method('getUrl')
            ->with($productId, $this->store)
            ->willReturn($productUrl);

        $this->assertEquals(
            $productUrl,
            $this->urlService->getUrlByStore($this->store)
        );
    }

    public function testGetUrlByStoreWithPage()
    {
        $pageId = 123;
        $pageUrl = 'http://example.com/page';

        $this->request->expects($this->once())
            ->method('getFullActionName')
            ->willReturn('cms_page_view');

        $this->request->expects($this->once())
            ->method('getParam')
            ->with('page_id')
            ->willReturn($pageId);

        $this->pageUrlRetriever->expects($this->once())
            ->method('getUrl')
            ->with($pageId, $this->store)
            ->willReturn($pageUrl);

        $this->assertEquals(
            $pageUrl,
            $this->urlService->getUrlByStore($this->store)
        );
    }

    public function testGetUrlByStoreWithHomepage()
    {
        $baseUrl = 'http://example.com/';

        $this->request->expects($this->once())
            ->method('getFullActionName')
            ->willReturn('cms_index_index');

        $this->store->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->assertEquals(
            $baseUrl,
            $this->urlService->getUrlByStore($this->store)
        );
    }
}
