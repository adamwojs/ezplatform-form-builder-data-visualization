<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReportBundle\DependencyInjection\Configuration\Parser;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\Parser\Templates;

final class ReportFieldTemplates extends Templates
{
    public const NODE_KEY = 'form_builder_report_field_templates';
    public const INFO = 'Settings for report field templates';
    public const INFO_TEMPLATE_KEY = 'Template file where to find block definition to display report fields';
}
