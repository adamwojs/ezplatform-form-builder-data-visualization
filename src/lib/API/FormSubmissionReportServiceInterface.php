<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\API;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\Report;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;

interface FormSubmissionReportServiceInterface
{
    public function generate(ContentInfo $content, ?string $languageCode = null): Report;

    // public function update(): void;

    // public funtion refresh(): void;
}
