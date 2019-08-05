<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Block;

use CommerceLeague\Seo\Block\HrefLang;
use CommerceLeague\Seo\Service\UrlService;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Element\Template\Context;

class HrefLangTest extends TestCase
{
    /**
     * @var MockObject|Context
     */
    protected $context;

    /**
     * @var MockObject|UrlService
     */
    protected $urlService;

    /**
     * @var MockObject|StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var MockObject|ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var MockObject|Store
     */
    protected $store;

    /**
     * @var HrefLang
     */
    protected $hrefLang;

    protected function setUp()
    {
        $this->context = $this->createMock(Context::class);
        $this->urlService = $this->createMock(UrlService::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);

        $this->context->expects($this->any())
            ->method('getStoreManager')
            ->willReturn($this->storeManager);

        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);

        $this->context->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->scopeConfig);

        $this->store = $this->createMock(Store::class);

        $this->hrefLang = new HrefLang(
            $this->context,
            $this->urlService
        );
    }

    public function testGetDefault()
    {
        $url = 'http://example.com/';

        $this->storeManager->expects($this->once())
            ->method('getDefaultStoreView')
            ->willReturn($this->store);

        $this->urlService->expects($this->once())
            ->method('getUrlByStore')
            ->willReturn($url);

        $this->assertEquals(
            $url,
            $this->hrefLang->getDefault()
        );
    }

    public function testGetAlternates()
    {
        $url = 'http://example.com/';
        $storeId = 123;
        $locale = 'en_US';

        $this->storeManager->expects($this->once())
            ->method('getStores')
            ->willReturn([$this->store]);

        $this->urlService->expects($this->once())
            ->method('getUrlByStore')
            ->willReturn($url);

        $this->store->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(
                'general/locale/code',
                'stores',
                $storeId
            )->willReturn($locale);

        $this->assertEquals(
            ['en-us' => $url],
            $this->hrefLang->getAlternates()
        );
    }
}
