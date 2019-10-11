<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\Core\ReportFieldType;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;
use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField\StatisticsReportField;
use AdamWojs\EzPlatformFormBuilderReport\SPI\ReportField\ReportFieldType;
use EzSystems\EzPlatformFormBuilder\FieldType\Model\FieldValue;
use Webmozart\Assert\Assert;

final class StatisticsReportFieldType implements ReportFieldType
{
    public function createEmpty(string $identifier): ReportField
    {
        return new StatisticsReportField($identifier);
    }

    public function update(ReportField $field, FieldValue $value): ReportField
    {
        Assert::isInstanceOf($field, StatisticsReportField::class);

        if ($value->getValue() !== null) {
            return $field->update((float)$value->getValue());
        }

        return $field;
    }
}
