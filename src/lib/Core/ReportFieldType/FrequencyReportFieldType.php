<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldType;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;
use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField\FrequencyReportField;
use AdamWojs\EzPlatformFormBuilderReport\SPI\ReportField\ReportFieldType;
use EzSystems\EzPlatformFormBuilder\FieldType\Model\FieldValue;

final class FrequencyReportFieldType implements ReportFieldType
{
    public function createEmpty(string $identifier): ReportField
    {
        return new FrequencyReportField($identifier);
    }

    public function update(ReportField $field, FieldValue $value): ReportField
    {
        $choices = (array)$value->getValue();

        if (empty($choices)) {
            return $field;
        }

        return $field->update($choices);
    }
}
