<?php

namespace GcProfiler\Event;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EventDispatcherCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition('event_dispatcher');
        $definition->setClass(EventDispatcherTracer::class);
    }
}
