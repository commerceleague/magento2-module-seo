<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Block\OpenGraph;

use CommerceLeague\Seo\Block\OpenGraph\Page;
use Magento\Cms\Model\Page as CmsPage;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    /**
     * @var MockObject|Context
     */
    protected $context;

    /**
     * @var MockObject|CmsPage
     */
    protected $cmsPage;

    /**
     * @var MockObject|UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Page
     */
    protected $page;

    protected function setUp()
    {
        $this->context = $this->createMock(Context::class);
        $this->cmsPage = $this->createMock(CmsPage::class);
        $this->urlBuilder = $this->createMock(UrlInterface::class);

        $this->context->expects($this->any())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilder);

        $this->page = new Page(
            $this->context,
            $this->cmsPage
        );
    }

    public function testGetOgTitleWithoutPage()
    {
        $this->cmsPage->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $this->assertEquals(
            '',
            $this->page->getOgTitle()
        );
    }

    public function testGetOgTitleFromMetaTitle()
    {
        $ogTitle = 'the title';

        $this->cmsPage->expects($this->once())
            ->method('getId')
            ->willReturn(123);

        $this->cmsPage->expects($this->exactly(2))
            ->method('getMetaTitle')
            ->willReturn($ogTitle);

        $this->assertEquals(
            $ogTitle,
            $this->page->getOgTitle()
        );
    }

    public function testGetOgTitleFromTitle()
    {
        $ogTitle = 'the title';

        $this->cmsPage->expects($this->once())
            ->method('getId')
            ->willReturn(123);

        $this->cmsPage->expects($this->once())
            ->method('getMetaTitle')
            ->willReturn(null);

        $this->cmsPage->expects($this->exactly(2))
            ->method('getTitle')
            ->willReturn($ogTitle);

        $this->assertEquals(
            $ogTitle,
            $this->page->getOgTitle()
        );
    }

    public function testGetOgDescriptionWithoutPage()
    {
        $this->cmsPage->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $this->assertEquals(
            '',
            $this->page->getOgDescription()
        );
    }

    public function testGetOgDescriptionFromMetaDescription()
    {
        $ogDescription = 'the description';

        $this->cmsPage->expects($this->once())
            ->method('getId')
            ->willReturn(123);

        $this->cmsPage->expects($this->exactly(2))
            ->method('getMetaDescription')
            ->willReturn($ogDescription);

        $this->assertEquals(
            $ogDescription,
            $this->page->getOgDescription()
        );
    }

    public function testGetOgDescriptionFromContent()
    {
        $ogDescription = 'the description';

        $this->cmsPage->expects($this->once())
            ->method('getId')
            ->willReturn(123);

        $this->cmsPage->expects($this->once())
            ->method('getMetaDescription')
            ->willReturn(null);

        $this->cmsPage->expects($this->exactly(2))
            ->method('getContent')
            ->willReturn($ogDescription);

        $this->assertEquals(
            $ogDescription,
            $this->page->getOgDescription()
        );
    }

    public function testGetOgUrlWithoutPage()
    {
        $this->cmsPage->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $this->assertEquals(
            '',
            $this->page->getOgUrl()
        );
    }

    public function testGetOgUrl()
    {
        $identifier = 'identifier';
        $url = 'http://example.com/identifier';

        $this->cmsPage->expects($this->once())
            ->method('getId')
            ->willReturn(123);

        $this->cmsPage->expects($this->once())
            ->method('getIdentifier')
            ->willReturn($identifier);

        $this->urlBuilder->expects($this->once())
            ->method('getUrl')
            ->willReturn($url);

        $this->assertEquals(
            $url,
            $this->page->getOgUrl()
        );
    }

    public function testGetOgImage()
    {
        $this->assertEquals(
            '',
            $this->page->getOgImage()
        );
    }
}
