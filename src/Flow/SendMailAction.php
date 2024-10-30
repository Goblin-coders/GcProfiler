<?php

namespace GcProfiler\Flow;
use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Flow\Dispatching\Action\FlowAction;
use Shopware\Core\Content\Flow\Dispatching\Action\SendMailAction as CoreSendMailAction;
use Shopware\Core\Content\Flow\Dispatching\StorableFlow;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Profiling\Profiler;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;

#[AsDecorator(decorates: CoreSendMailAction::class)]
class SendMailAction extends FlowAction
{
    public function __construct(
        #[AutowireDecorated]
        private readonly FlowAction $decorated,
        private readonly Connection $connection,
        #[Autowire(service: 'slugify')]
        private readonly Slugify $slugify
    ) {
    }

    public function requirements(): array
    {
        return $this->decorated->requirements();
    }

    public function handleFlow(StorableFlow $flow): void
    {
        $config = $flow->getConfig();
        if (!isset($config['mailTemplateId'])) {
            $this->decorated->handleFlow($flow);
            return;
        }

        $mailTemplateId = $config['mailTemplateId'];

        $name = $this->name($mailTemplateId);

        Profiler::trace('mail-' . $name, function() use ($flow) {
            $this->decorated->handleFlow($flow);
        }, 'sw-mail');
    }

    public static function getName(): string
    {
        return CoreSendMailAction::getName();
    }

    private function name(string $mailTemplateId): string
    {
        $name = $this->connection->fetchOne(
            'SELECT type.name
             FROM mail_template mail
                INNER JOIN mail_template_type_translation type
                ON mail.mail_template_type_id = type.mail_template_type_id
             WHERE mail.id = :id
               AND type.language_id = :language',
            [
                'id' => Uuid::fromHexToBytes($mailTemplateId),
                'language' => Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM)
            ]
        );

        $name = $name ?? $mailTemplateId;

        return $this->slugify->slugify(strtolower($name));
    }
}
