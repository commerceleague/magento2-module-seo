<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Config
 */
class Config extends AbstractHelper
{
    private const XML_PATH_WEBSITE_NAME = 'seo/website_information/name';
    private const XML_PATH_BUSINESS_NAME = 'seo/business_information/name';
    private const XML_PATH_BUSINESS_CUSTOMER_SERVICE_PHONE = 'seo/business_information/customer_service_phone';
    private const XML_PATH_SOCIAL_PROFILE_FACEBOOK = 'seo/social_profile/facebook';
    private const XML_PATH_SOCIAL_PROFILE_INSTAGRAM = 'seo/social_profile/instagram';

    /**
     * @return string|null
     */
    public function getWebsiteName(): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_WEBSITE_NAME);
    }

    /**
     * @return string|null
     */
    public function getBusinessName(): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_BUSINESS_NAME);
    }

    /**
     * @return string|null
     */
    public function getBusinessCustomerServicePhone(): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_BUSINESS_CUSTOMER_SERVICE_PHONE);
    }

    /**
     * @return string|null
     */
    public function getSocialProfileFacebook(): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SOCIAL_PROFILE_FACEBOOK);
    }

    /**
     * @return string|null
     */
    public function getSocialProfileInstagram(): ?string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SOCIAL_PROFILE_INSTAGRAM);
    }
}
