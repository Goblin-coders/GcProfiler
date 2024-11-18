<?php declare(strict_types=1);

namespace GcProfiler;

use GcProfiler\Event\EventDispatcherCompilerPass;
use GcProfiler\Listing\ListingCompilerPass;
use GcProfiler\Twig\TwigEnvironmentCompilerPass;
use Shopware\Core\Framework\Plugin;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GcProfiler extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TwigEnvironmentCompilerPass());
        $container->addCompilerPass(new EventDispatcherCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 50000);
        $container->addCompilerPass(new ListingCompilerPass(), PassConfig::TYPE_OPTIMIZE);

        parent::build($container);
    }
}
