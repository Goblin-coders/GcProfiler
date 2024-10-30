<?php

namespace GcProfiler\DAL;

use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Shopware\Core\Profiling\Profiler;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'product.loaded', method: 'start', priority: 30000)]
#[AsEventListener(event: 'product.loaded', method: 'stop', priority: -30000)]
#[AsEventListener(event: 'category.loaded', method: 'start', priority: 30000)]
#[AsEventListener(event: 'category.loaded', method: 'stop', priority: -30000)]
#[AsEventListener(event: 'media.loaded', method: 'start', priority: 30000)]
#[AsEventListener(event: 'media.loaded', method: 'stop', priority: -30000)]
class LoadedSubscribers
{
    public function start(EntityLoadedEvent $event): void
    {
        Profiler::start($event->getName(), 'dal-event', []);
    }

    public function stop(EntityLoadedEvent $event): void
    {
        Profiler::stop($event->getName());
    }
}
