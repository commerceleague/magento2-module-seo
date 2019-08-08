<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block\JsonLd;

use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * Class Product
 */
abstract class AbstractProduct extends Template
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ImageBuilder
     */
    protected $imageBuilder;

    /**
     * @param Template\Context $context
     * @param Registry $registry
     * @param ImageBuilder $imageBuilder
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        ImageBuilder $imageBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->imageBuilder = $imageBuilder;
    }

    /**
     * @return ProductModel
     */
    protected function getProduct(): ProductModel
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @param ProductModel $product
     * @return string
     */
    protected function getProductDescription(ProductModel $product): string
    {
        return strip_tags($product->getData('short_description') ?: $product->getData('description'));
    }

    /**
     * @param ProductModel $product
     * @return string
     */
    protected function getProductImage(ProductModel $product): string
    {
        return $this->imageBuilder->create($product, 'product_base_image')->getImageUrl();
    }
}