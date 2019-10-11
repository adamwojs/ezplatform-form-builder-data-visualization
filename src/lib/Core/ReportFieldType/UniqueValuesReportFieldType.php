<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldType;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;
use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField\UniqueValuesReportField;
use AdamWojs\EzPlatformFormBuilderReport\SPI\ReportField\ReportFieldType;
use EzSystems\EzPlatformFormBuilder\FieldType\Model\FieldValue;

final class UniqueValuesReportFieldType implements ReportFieldType
{
    public function createEmpty(string $identifier): ReportField
    {
        return new UniqueValuesReportField($identifier);
    }

    public function update(ReportField $field, FieldValue $value): ReportField
    {
        if (!empty($value)) {
            return $field->update((string) $value->getValue());
        }

        return $field;
    }
}
