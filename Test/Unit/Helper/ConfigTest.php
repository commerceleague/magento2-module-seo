<?php
/**
 */

namespace CommerceLeague\Seo\Test\Unit\Helper;

use CommerceLeague\Seo\Helper\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @var MockObject|ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Config
     */
    protected $config;

    protected function setUp()
    {
        $this->scopeConfig = $this->createPartialMock(
            ScopeConfigInterface::class,
            ['getValue', 'isSetFlag']
        );

        $objectManager = new ObjectManager($this);

        $this->config = $objectManager->getObject(
            Config::class,
            [
                'scopeConfig' => $this->scopeConfig
            ]
        );
    }

    public function testGetWebsiteName()
    {
        $websiteName = 'a website name';

        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('seo/website_information/name')
            ->willReturn($websiteName);

        $this->assertEquals(
            $websiteName,
            $this->config->getWebsiteName()
        );
    }

    public function testGetBusinessName()
    {
        $businessName = 'a business name';

        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('seo/business_information/name')
            ->willReturn($businessName);

        $this->assertEquals(
            $businessName,
            $this->config->getBusinessName()
        );
    }

    public function testGetBusinessCustomerServicePhone()
    {
        $businessCustomerServicePhone = '12345678';

        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('seo/business_information/customer_service_phone')
            ->willReturn($businessCustomerServicePhone);

        $this->assertEquals(
            $businessCustomerServicePhone,
            $this->config->getBusinessCustomerServicePhone()
        );
    }

    public function testGetSocialProfileFacebook()
    {
        $socialProfileFacebook = 'https://facebook.com/example';

        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('seo/social_profile/facebook')
            ->willReturn($socialProfileFacebook);

        $this->assertEquals(
            $socialProfileFacebook,
            $this->config->getSocialProfileFacebook()
        );
    }

    public function testGetSocialProfileInstagram()
    {
        $socialProfileInstagram = 'https://instagram.com/example';

        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('seo/social_profile/instagram')
            ->willReturn($socialProfileInstagram);

        $this->assertEquals(
            $socialProfileInstagram,
            $this->config->getSocialProfileInstagram()
        );
    }
}