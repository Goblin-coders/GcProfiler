<?php declare(strict_types=1);

namespace GcProfiler;

use GcProfiler\Twig\TwigEnvironmentCompilerPass;
use Shopware\Core\Framework\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GcProfiler extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TwigEnvironmentCompilerPass());

        parent::build($container);
    }
}
