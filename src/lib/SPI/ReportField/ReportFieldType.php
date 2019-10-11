<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\SPI\ReportField;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;
use EzSystems\EzPlatformFormBuilder\FieldType\Model\FieldValue;

interface ReportFieldType
{
    public function createEmpty(string $identifier): ReportField;

    public function update(ReportField $field, FieldValue $value): ReportField;
}
