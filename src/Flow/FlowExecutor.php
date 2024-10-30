<?php

namespace GcProfiler\Flow;

use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Flow\Dispatching\FlowExecutor as CoreFlowExecutor;
use Shopware\Core\Content\Flow\Dispatching\StorableFlow;
use Shopware\Core\Content\Flow\Dispatching\Struct\ActionSequence;
use Shopware\Core\Content\Flow\Dispatching\Struct\Flow;
use Shopware\Core\Content\Flow\Dispatching\Struct\IfSequence;
use Shopware\Core\Content\Flow\Dispatching\Struct\Sequence;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Profiling\Profiler;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;

#[AsDecorator(decorates: CoreFlowExecutor::class)]
class FlowExecutor extends CoreFlowExecutor
{
    public function __construct(
        #[AutowireDecorated]
        private readonly CoreFlowExecutor $decorated,
        private readonly Connection $connection,
        #[Autowire(service: 'slugify')]
        private readonly Slugify $slugify
    ) {
    }

    public function execute(Flow $flow, StorableFlow $event): void
    {
        $name = $this->getName($flow->getId());

        Profiler::trace('flow-' . $name, function() use ($flow, $event) {
            $this->decorated->execute($flow, $event);
        }, 'sw-flows');
    }

    public function executeSequence(?Sequence $sequence, StorableFlow $event): void
    {
        $this->decorated->executeSequence($sequence, $event);
    }

    public function executeAction(ActionSequence $sequence, StorableFlow $event): void
    {
        $this->decorated->executeAction($sequence, $event);
    }

    public function executeIf(IfSequence $sequence, StorableFlow $event): void
    {
        $this->decorated->executeIf($sequence, $event);
    }

    private function getName(string $flowId): string
    {
        $name = (string) $this->connection->fetchOne(
            'SELECT name FROM flow WHERE id = :id',
            ['id' => Uuid::fromHexToBytes($flowId)]
        );

        return $this->slugify->slugify(strtolower($name));
    }
}
