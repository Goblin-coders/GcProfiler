<?php

namespace GcProfiler\Listing;

use GcProfiler\Slug;
use Shopware\Core\Content\Product\SalesChannel\Listing\Processor\AbstractListingProcessor;
use Shopware\Core\Content\Product\SalesChannel\Listing\Processor\AggregationListingProcessor;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Profiling\Profiler;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

#[AsDecorator(decorates: AggregationListingProcessor::class, onInvalid: ContainerInterface::NULL_ON_INVALID_REFERENCE)]
class ListingProcessorDecorator extends AbstractListingProcessor
{
    public function __construct(
        #[AutowireDecorated]
        private readonly AbstractListingProcessor $decorated
    ) {
    }

    public function getDecorated(): AbstractListingProcessor
    {
        return $this->decorated;
    }

    public function prepare(Request $request, Criteria $criteria, SalesChannelContext $context): void
    {
        Profiler::trace(Slug::slug($this->decorated::class, 'prepare'), fn() => $this->getDecorated()->prepare($request, $criteria, $context));
    }

    public function process(Request $request, ProductListingResult $result, SalesChannelContext $context): void
    {
        Profiler::trace(Slug::slug($this->decorated::class, 'process'), fn() => $this->getDecorated()->process($request, $result, $context));
    }
}
