<?php

namespace GcProfiler\Listing;

use Cocur\Slugify\Slugify;
use Shopware\Core\Content\Product\SalesChannel\Listing\Processor\AbstractListingProcessor;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Profiling\Profiler;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\HttpFoundation\Request;

#[AsDecorator(decorates: '')]
class ListingProcessorDecorator extends AbstractListingProcessor
{
    public function __construct(
        #[AutowireDecorated]
        private readonly AbstractListingProcessor $decorated,
        #[Autowire(service: 'slugify')]
        private readonly Slugify $slugify
    ) {
    }

    public function getDecorated(): AbstractListingProcessor
    {
        return $this->decorated;
    }

    public function prepare(Request $request, Criteria $criteria, SalesChannelContext $context): void
    {
        Profiler::trace($this->slugify('prepare'), fn() => $this->getDecorated()->prepare($request, $criteria, $context));
    }

    public function process(Request $request, ProductListingResult $result, SalesChannelContext $context): void
    {
        Profiler::trace($this->slugify('process'), fn() => $this->getDecorated()->process($request, $result, $context));
    }

    private function slugify(string $suffix): string
    {
        return $this->slugify->slugify($this->decorated::class . '.' . $suffix, '-');
    }

}
