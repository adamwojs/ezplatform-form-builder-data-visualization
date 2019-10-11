<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\API\Values;

/**
 * @internal
 */
final class ReportBuilder
{
    /** @var \AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField[] */
    private $fields = [];

    public function addField(ReportField $field): ReportField
    {
        $this->fields[$field->getName()] = $field;

        return $field;
    }

    public function hasField(string $identifier): bool
    {
        return isset($this->fields[$identifier]);
    }

    public function getField(string $identifier): ReportField
    {
        return $this->fields[$identifier];
    }

    public function build(): Report
    {
        return new Report($this->fields);
    }
}
