<?php
declare(strict_types=1);
/**
 */

namespace CommerceLeague\Seo\Block\JsonLd;

use CommerceLeague\Seo\Block\JsonLdInterface;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\GroupedProduct\Model\Product\Type\Grouped as GroupedType;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\ProductFactory as ProductSchemaFactory;
use Spatie\SchemaOrg\Product as ProductSchema;
use Spatie\SchemaOrg\AggregateOfferFactory as AggregateOfferSchemaFactory;
use Spatie\SchemaOrg\AggregateOffer as AggregateOfferSchema;
use Spatie\SchemaOrg\OfferFactory as OfferSchemaFactory;
use Spatie\SchemaOrg\Offer as OfferSchema;

/**
 * Class Product
 */
class Product extends Template implements JsonLdInterface
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
     * @var ProductSchemaFactory
     */
    private $productSchemaFactory;

    /**
     * @var AggregateOfferSchemaFactory
     */
    private $aggregateOfferSchemaFactory;

    /**
     * @var OfferSchemaFactory
     */
    private $offerSchemaFactory;

    /**
     * @param Template\Context $context
     * @param Registry $registry
     * @param ImageBuilder $imageBuilder
     * @param ProductSchemaFactory $productSchemaFactory
     * @param AggregateOfferSchemaFactory $aggregateOfferSchemaFactory
     * @param OfferSchemaFactory $offerSchemaFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        ImageBuilder $imageBuilder,
        ProductSchemaFactory $productSchemaFactory,
        AggregateOfferSchemaFactory $aggregateOfferSchemaFactory,
        OfferSchemaFactory $offerSchemaFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->imageBuilder = $imageBuilder;
        $this->productSchemaFactory = $productSchemaFactory;
        $this->aggregateOfferSchemaFactory = $aggregateOfferSchemaFactory;
        $this->offerSchemaFactory = $offerSchemaFactory;
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
            ->description($this->getProductDescription())
            ->sku($product->getSku())
            ->url($product->getProductUrl())
            ->image($this->getProductImage($product));

        $offers = [];

        if ($product->getTypeId() === GroupedType::TYPE_CODE) {
            $offers = $offers + $this->getGroupedProductOffers();
        }

        if (!empty($offers)) {
            $productSchema->offers($offers);
        }

        return $productSchema->toScript();
    }

    /**
     * @return ProductModel
     */
    private function getProduct(): ProductModel
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @return string
     */
    private function getProductDescription(): string
    {
        $product = $this->getProduct();
        return strip_tags($product->getData('short_description') ?: $product->getData('description'));
    }

    /**
     * @param ProductModel $product
     * @return string
     */
    private function getProductImage(ProductModel $product): string
    {
        return $this->imageBuilder->create($product, 'product_base_image')->getImageUrl();
    }

    /**
     * @return OfferSchema[]
     */
    private function getGroupedProductOffers(): array
    {
        $product = $this->getProduct();
        /** @var GroupedType $productType */
        $productType = $product->getTypeInstance();

        /** @var AggregateOfferSchema $aggregateOfferSchema */
        $aggregateOfferSchema = $this->aggregateOfferSchemaFactory->create();

        $aggregateOfferSchema->priceCurrency($product->getStore()->getCurrentCurrencyCode())
            ->name($product->getName())
            ->availability($product->isInStock() ? ItemAvailability::InStock : ItemAvailability::OutOfStock);

        $associatedProducts = $productType->getAssociatedProducts($product);
        $offerCount = count($associatedProducts);
        $prices = [];
        $offerSchemas = [];

        /** @var ProductModel $associatedProduct */
        foreach ($associatedProducts as $associatedProduct) {
            $prices[] = $associatedProduct->getFinalPrice();

            /** @var OfferSchema $offerSchema */
            $offerSchema = $this->offerSchemaFactory->create();

            $offerSchema->name($product->getName())
                ->price($associatedProduct->getFinalPrice())
                ->sku($associatedProduct->getSku())
                ->image($this->getProductImage($product));

            $offerSchemas[] = $offerSchema;
        }

        $aggregateOfferSchema->offerCount($offerCount)
            ->highPrice(max($prices))
            ->lowPrice(min($prices));

        if (!empty($offerSchemas)) {
            $aggregateOfferSchema->offers($offerSchemas);
        }

        return [$aggregateOfferSchema];
    }


    /**
     * @inheritDoc
     */
    public function toHtml()
    {
        return $this->getScript();
    }
}
