<?php declare(strict_types=1);

namespace GoblinCoders;

use Shopware\Core\Framework\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GcTwigProfiler extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TwigEnvironmentCompilerPass());
        parent::build($container);
    }
}
