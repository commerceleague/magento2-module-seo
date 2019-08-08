<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block\JsonLd;

use CommerceLeague\Seo\Block\JsonLdInterface;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Spatie\SchemaOrg\ProductFactory as ProductSchemaFactory;
use Spatie\SchemaOrg\Product as ProductSchema;

/**
 * Class Product
 */
class Product extends AbstractProduct implements JsonLdInterface
{
    /**
     * @var ProductSchemaFactory
     */
    private $productSchemaFactory;

    /**
     * @var array
     */
    private $productOfferGenerators;

    /**
     * @param Template\Context $context
     * @param Registry $registry
     * @param ImageBuilder $imageBuilder
     * @param ProductSchemaFactory $productSchemaFactory
     * @param ProductOfferGeneratorInterface[] $productOfferGenerators
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        ImageBuilder $imageBuilder,
        ProductSchemaFactory $productSchemaFactory,
        array $productOfferGenerators,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $imageBuilder,
            $data
        );
        $this->productSchemaFactory = $productSchemaFactory;
        $this->productOfferGenerators = $productOfferGenerators;
    }

    /**
     * @inheritDoc
     */
    public function getScript(): string
    {
        $product = $this->getProduct();

        /** @var ProductSchema $productSchema */
        $productSchema = $this->productSchemaFactory->create();

        $productSchema->name($product->getName())
            ->description($this->getProductDescription($product))
            ->sku($product->getSku())
            ->url($product->getProductUrl())
            ->image($this->getProductImage($product));

        $offers = [];

        if (isset($this->productOfferGenerators[$product->getTypeId()])) {
            $offers += $this->productOfferGenerators[$product->getTypeId()]->getOffers($product);
        }

        if (!empty($offers)) {
            $productSchema->offers($offers);
        }

        return $productSchema->toScript();
    }

    /**
     * @inheritDoc
     */
    public function toHtml()
    {
        return $this->getScript();
    }
}
