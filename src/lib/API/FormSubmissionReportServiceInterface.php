<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\API;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\Report;
use eZ\Publish\API\Repository\Values\Content\Content;

interface FormSubmissionReportServiceInterface
{
    public function generate(Content $content, ?string $languageCode = null): Report;
}
