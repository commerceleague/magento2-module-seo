<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Service\UrlRetriever;

use CommerceLeague\Seo\Service\UrlRetriever\PageUrlRetriever;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\Page;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\CmsUrlRewrite\Model\CmsPageUrlPathGenerator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Store\Model\Store;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageUrlRetrieverTest extends TestCase
{
    /**
     * @var MockObject|PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * @var MockObject|PageResource
     */
    protected $pageResource;

    /**
     * @var MockObject|CmsPageUrlPathGenerator
     */
    protected $cmsPageUrlPathGenerator;

    /**
     * @var MockObject|Store
     */
    protected $store;

    /**
     * @var MockObject|Page
     */
    protected $page;

    /**
     * @var PageUrlRetriever
     */
    protected $pageUrlRetriever;

    protected function setUp()
    {
        $this->pageRepository = $this->createMock(PageRepositoryInterface::class);
        $this->pageResource = $this->createMock(PageResource::class);
        $this->cmsPageUrlPathGenerator = $this->createMock(CmsPageUrlPathGenerator::class);
        $this->store = $this->createMock(Store::class);
        $this->page = $this->createMock(Page::class);

        $this->pageUrlRetriever = new PageUrlRetriever(
            $this->pageRepository,
            $this->pageResource,
            $this->cmsPageUrlPathGenerator
        );
    }

    public function testGetUrlReturnsNull()
    {
        $identifier = 'identifier';

        $this->pageRepository->expects($this->once())
            ->method('getById')
            ->with($identifier)
            ->willThrowException(new LocalizedException(
                new Phrase('')
            ));

        $this->assertNull(
            $this->pageUrlRetriever->getUrl($identifier, $this->store)
        );
    }

    public function testGetUrlReturnsUrl()
    {
        $identifier = 'identifier';
        $storeId = 123;
        $pageId = 456;
        $baseUrl = 'http://example.com/';

        $this->pageRepository->expects($this->at(0))
            ->method('getById')
            ->with($identifier)
            ->willReturn($this->page);

        $this->page->expects($this->once())
            ->method('getIdentifier')
            ->willReturn($identifier);

        $this->store->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $this->pageResource->expects($this->once())
            ->method('checkIdentifier')
            ->with($identifier, $storeId)
            ->willReturn($pageId);

        $this->pageRepository->expects($this->at(1))
            ->method('getById')
            ->with($pageId)
            ->willReturn($this->page);

        $this->cmsPageUrlPathGenerator->expects($this->once())
            ->method('getUrlPath')
            ->with($this->page)
            ->willReturn($identifier);

        $this->store->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn($baseUrl);

        $this->assertEquals(
            $baseUrl . $identifier,
            $this->pageUrlRetriever->getUrl($identifier, $this->store)
        );
    }
}
