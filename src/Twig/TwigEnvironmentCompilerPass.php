<?php

namespace GcProfiler\Twig;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigEnvironmentCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $twigEnvironment = $container->findDefinition('twig');
        $twigEnvironment->setPublic(true);
        $twigEnvironment->setClass(TwigDecoration::class);
    }
}
