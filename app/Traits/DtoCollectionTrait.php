<?php

namespace App\Traits;

trait DtoCollectionTrait
{
    abstract protected static function fromArray(array $row): static;
    abstract protected function toArray(): array;

    public static function array(array $row): array
    {
        return static::fromArray($row)->toArray();
    }

    public static function collection(array $rows):array
    {
        return array_map(
            fn (array $row) => static::array($row),
            $rows
        );
    }
}