<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReportBundle\DependencyInjection\CompilerPass;

use AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldTypeRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ReportFieldTypeRegistryCompilerPass implements CompilerPassInterface
{
    public const REPORT_FIELD_TYPE_SERVICE_TAG = 'ezplatform.form_builder.report.field_type';

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(ReportFieldTypeRegistry::class)) {
            return;
        }

        $registry = $container->getDefinition(ReportFieldTypeRegistry::class);

        $reportFieldTypeServiceIds = $container->findTaggedServiceIds(self::REPORT_FIELD_TYPE_SERVICE_TAG);
        foreach ($reportFieldTypeServiceIds as $id => $tags) {
            foreach ($tags as $attributes) {
                $registry->addMethodCall(
                    'register',
                    [
                        $attributes['alias'],
                        new Reference($id),
                    ]
                );
            }
        }
    }
}
