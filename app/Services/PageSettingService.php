<?php

namespace App\Services;

use App\Contracts\Repositories\PageSettingRepositoryInterface;
use App\Repositories\PageSettingRepository;

class PageSettingService
{
    private PageSettingRepositoryInterface $pageSettingRepository;

    public function __construct(
        ?PageSettingRepositoryInterface $pageSettingRepository = null,
    )
    {
        $this->pageSettingRepository = $pageSettingRepository ?? new PageSettingRepository();
    }

    public function getPage(string $page): array 
    {
        return $this->pageSettingRepository->getPage($page);
    }

    
    public function updateSetting(string $page, string $key, mixed $value): bool 
    {
           return $this->pageSettingRepository->updateSetting($page,$key,$value);
    }

    
    public function updateWhere(string $page, array $settings): bool
    {
        return $this->pageSettingRepository->updateWhere($page,$settings);
    }

    
    public function resetPageFromJson(string $page): bool {
        return $this->pageSettingRepository->resetPageFromJson($page);
    }

    public function getBool(string $page, string $key, bool $default = false): bool
    {
        return $this->pageSettingRepository->getBool($page,$key,$default);
    }
}