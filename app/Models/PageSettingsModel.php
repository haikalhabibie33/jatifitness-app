<?php 

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class PageSettingsModel extends Model {
    protected $table = 'page_settings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['page', 'settings_json', 'updatedAt'];
    protected $useTimestamps = false;

    public function getPage(string $page): array {
        $row = $this->select('settings_json')->where('page', $page)->first();
        if (!$row || empty($row['settings_json'])) {
            return [];
        }
        $data = json_decode($row['settings_json'], true);
        return is_array($data) ? $data : [];
    }

    
    public function updateSetting(string $page, string $key, mixed $value): bool {
            $settings = $this->getPage($page);
            $settings[$key] = $value;

            return $this->where('page', $page)->set([
                'settings_json' => json_encode($settings, JSON_UNESCAPED_SLASHES),
                'updatedAt'     => date('Y-m-d H:i:s'),
            ])->update();
    }

    
    public function updateWhere(string $page, array $settings): bool
    {
        return $this->where('page', $page)->set([
            'settings_json' => json_encode($settings, JSON_UNESCAPED_SLASHES),
            'updatedAt'     => date('Y-m-d H:i:s'),
        ])->update();
    }

    
    public function resetPageFromJson(string $page): bool {
        $jsonPath = APPPATH . "Database/Seeds/json/pages/{$page}.json";

        if (! is_file($jsonPath)) {
            return false;
        }

        $content = file_get_contents($jsonPath);
        $data = json_decode($content, true);

        if (! is_array($data)) {
            return false;
        }

        return $this->where('page', $page)->set([
            'settings_json' => json_encode($data, JSON_UNESCAPED_SLASHES),
            'updatedAt'     => Time::now()->toDateTimeString(),
        ])->update();
    }

    public function getBool(string $page, string $key, bool $default = false): bool
    {
        $settings = $this->getPage($page);

        if (!isset($settings[$key])) {
            return $default;
        }

        return filter_var($settings[$key], FILTER_VALIDATE_BOOLEAN);
    }

}