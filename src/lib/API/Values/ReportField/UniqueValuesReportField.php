<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;

use AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField;

final class UniqueValuesReportField extends ReportField
{
    /** @var string[] */
    private $values;

    public function __construct(string $identifier, array $values = [])
    {
        parent::__construct($identifier);

        $this->values = $values;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @internal
     */
    public function update(string $value): self
    {
        if (in_array($value, $this->values)) {
            $this->values[] = $value;
        }

        return $this;
    }
}
