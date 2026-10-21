<?php

namespace App\DTO;

interface ViewDtoInterface
{
    public static function array(array $row): array;
    public static function collection(array $rows): array;
}