<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\CLI\CLI;
use Config\Database;
use JsonException;

class MembershipPlanSeeder extends Seeder
{
    protected string $jsonPath = APPPATH . 'Database/Seeds/json/non-pages/membership-plans.json';

    public function run()
    {
        // ==================================================
        // 1. Load JSON
        // ==================================================
        try {
            $data = json_decode(
                file_get_contents($this->jsonPath),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            CLI::error('Invalid JSON: ' . $e->getMessage());
            return;
        }

        if (!is_array($data)) {
            CLI::error('membership-plans.json must be an ARRAY of objects.');
            return;
        }

        // ==================================================
        // 2. Validation (Plan domain)
        // ==================================================
        $required = [
            'code',
            'name',
            'price',
            'duration_days',
            'is_active',
            "daily_limit",
            'target_member_type',
            'audience_scope',
        ];

        foreach ($data as $i => $row) {
            if (!is_array($row)) {
                CLI::error("Item #{$i} is not an object.");
                return;
            }

            // required keys
            foreach ($required as $key) {
                if (!array_key_exists($key, $row)) {
                    CLI::error("Missing required key '{$key}' in item #{$i}");
                    return;
                }
            }

            // type validation
            if (!is_string($row['code'])) {
                CLI::error("code must be string in item #{$i}");
                return;
            }

            if (!is_string($row['name'])) {
                CLI::error("name must be string in item #{$i}");
                return;
            }

            if (!is_int($row['price'])) {
                CLI::error("price must be integer in item #{$i}");
                return;
            }

            if (!is_int($row['duration_days'])) {
                CLI::error("duration_days must be integer in item #{$i}");
                return;
            }

            if (!is_bool($row['is_active'])) {
                CLI::error("is_active must be boolean in item #{$i}");
                return;
            }
            if (!is_int($row['daily_limit'])) {
                CLI::error("daily_limit must be integer in item #{$i}");
                return;
            }

            if (!in_array($row['target_member_type'], ['REGULAR', 'STUDENT'], true)) {
                CLI::error("target_member_type must be REGULAR or STUDENT in item #{$i}");
                return;
            }

            if (!in_array($row['audience_scope'], ['CASUAL_ONLY', 'MEMBER_ONLY', 'BOTH'], true)) {
                CLI::error("audience_scope must be CASUAL_ONLY or MEMBER_ONLY or BOTH in item #{$i}");
                return;
            }
        }

        // ==================================================
        // 3. Insert to database
        // ==================================================
        $db = Database::connect();

        if (!$db->tableExists('membership_plans')) {
            CLI::error("Table 'membership_plans' not found.");
            return;
        }

        $now = date('Y-m-d H:i:s');

        // enrich data with timestamps
        foreach ($data as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }
        unset($row);

        CLI::write('Clearing membership_plans table...', 'yellow');
        $db->table('membership_plans')->truncate();

        CLI::write('Inserting membership plans...', 'yellow');
        $db->table('membership_plans')->insertBatch($data);

        CLI::write('Inserted membership plans: ' . count($data), 'green');
    }
}