<?php

namespace GcProfiler\Listing;

use GcProfiler\Slug;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter\AbstractListingFilterHandler;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingResult;
use Shopware\Core\Profiling\Profiler;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class AggregationDecorator extends AbstractListingFilterHandler
{
    public function __construct(
        private readonly AbstractListingFilterHandler $decorated
    ) {
    }

    public function getDecorated(): AbstractListingFilterHandler
    {
        return $this->decorated;
    }

    public function create(Request $request, SalesChannelContext $context): ?Filter
    {
        return Profiler::trace(
            Slug::slug($this->decorated::class, 'create'),
            function () use ($request, $context) {
                return $this->decorated->create($request, $context);
            },
        );
    }

    public function process(Request $request, ProductListingResult $result, SalesChannelContext $context): void
    {
        Profiler::trace(
            Slug::slug($this->decorated::class, 'process'),
            function () use ($request, $result, $context) {
                $this->decorated->process($request, $result, $context);
            },
        );
    }
}
