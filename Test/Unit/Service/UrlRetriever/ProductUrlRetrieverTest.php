<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Service\UrlRetriever;

use CommerceLeague\Seo\Service\UrlRetriever\ProductUrlRetriever;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;
use Magento\Store\Model\Store;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator;

class ProductUrlRetrieverTest extends TestCase
{
    /**
     * @var MockObject|Registry
     */
    protected $registry;

    /**
     * @var MockObject|ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var MockObject|ProductUrlPathGenerator
     */
    protected $productUrlPathGenerator;

    /**
     * @var MockObject|Store
     */
    protected $store;

    /**
     * @var MockObject|Product
     */
    protected $product;

    /**
     * @var ProductUrlRetriever
     */
    protected $productUrlRetriever;

    protected function setUp()
    {
        $this->registry = $this->createMock(Registry::class);
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->productUrlPathGenerator = $this->createMock(ProductUrlPathGenerator::class);
        $this->store = $this->createMock(Store::class);
        $this->product = $this->createMock(Product::class);

        $this->productUrlRetriever = new ProductUrlRetriever(
            $this->registry,
            $this->productRepository,
            $this->productUrlPathGenerator
        );
    }

    public function testGetUrlGetProductFromRegistry()
    {
        $identifier = 123;
        $path = 'product.html';
        $baseUrl = 'http://example.com/';

        $this->registry->expects($this->once())
            ->method('registry')
            ->with('product')
            ->willReturn($this->product);

        $this->productRepository->expects($this->never())
            ->method('get');

        $this->productUrlPathGenerator->expects($this->once())
            ->method('getUrlPathWithSuffix')
            ->with($this->product)
            ->willReturn($path);

        $this->store->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->assertEquals(
            $baseUrl . $path,
            $this->productUrlRetriever->getUrl($identifier, $this->store)
        );
    }

    public function testGetUrlGetProductFromRepository()
    {
        $identifier = 123;
        $storeId = 45;
        $path = 'product.html';
        $baseUrl = 'http://example.com/';

        $this->registry->expects($this->once())
            ->method('registry')
            ->with('product')
            ->willReturn(null);

        $this->store->expects($this->exactly(2))
            ->method('getId')
            ->willReturn($storeId);

        $this->productRepository->expects($this->once())
            ->method('getById')
            ->with($identifier, false, $storeId)
            ->willReturn($this->product);

        $this->productUrlPathGenerator->expects($this->once())
            ->method('getUrlPathWithSuffix')
            ->with($this->product, $storeId)
            ->willReturn($path);

        $this->store->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->assertEquals(
            $baseUrl . $path,
            $this->productUrlRetriever->getUrl($identifier, $this->store)
        );
    }
}
