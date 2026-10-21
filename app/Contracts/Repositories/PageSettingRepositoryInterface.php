<?php

namespace App\Contracts\Repositories;

interface PageSettingRepositoryInterface
{
    public function getPage(string $page): array;
    public function updateSetting(string $page, string $key, mixed $value): bool;
    public function updateWhere(string $page, array $settings): bool;
    public function resetPageFromJson(string $page): bool;
    public function getBool(string $page, string $key, bool $default): bool;
}