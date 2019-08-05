<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block;

use CommerceLeague\Seo\Service\UrlService;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Class HrefLang
 */
class HrefLang extends Template
{
    /**
     * @var UrlService
     */
    private $urlService;

    /**
     * @param Template\Context $context
     * @param UrlService $urlService
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        UrlService $urlService,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->urlService = $urlService;
    }

    /**
     * @return null|string
     * @throws NoSuchEntityException
     */
    public function getDefault(): ?string
    {
        $defaultStore = $this->_storeManager->getDefaultStoreView();
        return $this->urlService->getUrlByStore($defaultStore);
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getAlternates(): array
    {
        $data = [];
        $stores = $this->_storeManager->getStores();
        foreach ($stores as $store) {
            if ($url = $this->urlService->getUrlByStore($store)) {
                $data[$this->getLocaleCodeByStore($store)] = $url;
            }
        }

        return $data;
    }

    /**
     * @param StoreInterface $store
     * @return string
     */
    private function getLocaleCodeByStore(StoreInterface $store): string
    {
        $localeCode = $this->_scopeConfig->getValue('general/locale/code', 'stores', $store->getId());
        return str_replace('_', '-', strtolower($localeCode));
    }
}
