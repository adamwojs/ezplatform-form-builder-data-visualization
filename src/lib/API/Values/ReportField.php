<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformFormBuilderReport\API\Values;

abstract class ReportField
{
    /** @var string */
    protected $name;

    public function __construct(string $identifier)
    {
        $this->name = $identifier;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
