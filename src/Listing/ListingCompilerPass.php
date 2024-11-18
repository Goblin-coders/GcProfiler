<?php

namespace GcProfiler\Listing;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ListingCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $tagged = $container->findTaggedServiceIds('shopware.listing.filter.handler');

        foreach ($tagged as $id => $tags) {
            $definition = $container->getDefinition($id);

            if ($definition->getClass() === AggregationDecorator::class) {
                continue;
            }

            $decorator = new Definition(
                AggregationDecorator::class,
                [new Reference($id)]
            );

            $decorator->setDecoratedService($id);

            $container->setDefinition($id . '.trace.decorator', $decorator);
        }
    }
}
