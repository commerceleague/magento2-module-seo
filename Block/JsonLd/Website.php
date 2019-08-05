<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block\JsonLd;

use CommerceLeague\Seo\Block\JsonLdInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Spatie\SchemaOrg\WebSiteFactory as WebsiteSchemaFactory;
use Spatie\SchemaOrg\WebSite as WebsiteSchema;

/**
 * Class Website
 */
class Website extends Template implements JsonLdInterface
{
    /**
     * @var WebsiteSchemaFactory
     */
    private $websiteSchemaFactory;

    /**
     * @param Template\Context $context
     * @param WebsiteSchemaFactory $websiteSchemaFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        WebsiteSchemaFactory $websiteSchemaFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->websiteSchemaFactory = $websiteSchemaFactory;
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        /** @var WebsiteSchema $websiteSchema */
        $websiteSchema = $this->websiteSchemaFactory->create();

        $websiteSchema->url($this->getBaseUrl())
            ->name($this->getStoreName());

        return $websiteSchema->toScript();
    }

    /**
     * @return string
     */
    private function getStoreName(): string
    {
        return (string)$this->_scopeConfig->getValue(
            'general/store_information/name',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return $this->getScript();
    }
}
