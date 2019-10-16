<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\Core\EventSubscriber;

use AdamWojs\EzPlatformFormBuilderReport\Core\FormService;
use AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldTypeRegistry;
use EzSystems\EzPlatformFormBuilder\Definition\FieldAttributeDefinitionBuilder;
use EzSystems\EzPlatformFormBuilder\Event\FieldDefinitionEvent;
use EzSystems\EzPlatformFormBuilder\Event\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class FormFieldDefinitionSubscriber implements EventSubscriberInterface
{
    /** @var \AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldTypeRegistry */
    private $reportFieldTypeRegistry;

    public function __construct(ReportFieldTypeRegistry $reportFieldTypeRegistry)
    {
        $this->reportFieldTypeRegistry = $reportFieldTypeRegistry;
    }

    public function onFieldDefinition(FieldDefinitionEvent $event): void
    {
        $fieldDefinition = $event->getDefinitionBuilder();

        if (!$this->reportFieldTypeRegistry->has($fieldDefinition->getIdentifier())) {
            // field type doesn't support analysis
            return;
        }

        $attributeDefinition = new FieldAttributeDefinitionBuilder();
        $attributeDefinition->setIdentifier(FormService::ENABLE_SUMMARY_ATTRIBUTE);
        $attributeDefinition->setType('checkbox');
        $attributeDefinition->setName('Enable summary');
        $attributeDefinition->setDefaultValue(true);

        $fieldDefinition->addAttribute($attributeDefinition->buildDefinition());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::FIELD_DEFINITION => 'onFieldDefinition',
        ];
    }
}
