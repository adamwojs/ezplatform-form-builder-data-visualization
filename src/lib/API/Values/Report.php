<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\API\Values;

use ArrayIterator;
use Iterator;
use IteratorAggregate;

final class Report implements IteratorAggregate
{
    /** @var \AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField[] */
    private $fields;

    /**
     * @param \AdamWojs\EzPlatformFormBuilderReport\API\Values\ReportField[] $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->fields);
    }
}
