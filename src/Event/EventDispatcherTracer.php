<?php

namespace GcProfiler\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use Shopware\Core\Profiling\Profiler;
use Symfony\Component\EventDispatcher\Debug\WrappedListener;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventDispatcherTracer extends EventDispatcher
{
    protected function callListeners(iterable $listeners, string $eventName, object $event): void
    {
        $stoppable = $event instanceof StoppableEventInterface;

        foreach ($listeners as $listener) {
            if ($stoppable && $event->isPropagationStopped()) {
                break;
            }

            try {
                if ($listener instanceof WrappedListener) {
                    $class = $listener->getPretty();
                } else if (is_array($listener)) {
                    $class = get_class($listener[0]) . '::' . $listener[1];
                } else {
                    $class = get_class($listener);
                }
            } catch (\Throwable) {
                $class = 'Unknown';
            }

            $key = $class . '::on::' . $eventName;

            Profiler::trace($key, function () use ($listener, $event, $eventName) {
                $listener($event, $eventName, $this);
            });
        }
    }
}
