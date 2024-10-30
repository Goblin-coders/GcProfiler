<?php

namespace GcProfiler\Twig;

use Shopware\Core\Framework\Adapter\Twig\TwigEnvironment;
use Shopware\Core\Profiling\Profiler;
use Twig\Template;

class TwigDecoration extends TwigEnvironment
{
    public function loadTemplate(string $cls, string $name, ?int $index = null): Template
    {
        return Profiler::trace($name, function() use ($cls, $name, $index) {
            return parent::loadTemplate($cls, $name, $index);
        }, 'sw-template');
    }
}
