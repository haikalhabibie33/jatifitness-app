<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\CLI\CLI;
use Config\Database;
use JsonException;

class MembershipSeeder extends Seeder
{
    protected string $jsonPath = APPPATH . 'Database/Seeds/json/non-pages/membership.json';

    public function run()
    {
        // =============================
        // 1) Load JSON file
        // =============================
        try {
            $data = json_decode(
                file_get_contents($this->jsonPath),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            CLI::error("Invalid JSON: " . $e->getMessage());
            return;
        }

        // =============================
        // 2) Manual validation (simple, stable)
        // =============================

        if (!is_array($data)) {
            CLI::error("membership.json must be an ARRAY of objects.");
            return;
        }

        $required = ['name', 'gender', 'phone', 'email', 'address', 'status', 'start_date', 'end_date'];

        foreach ($data as $i => $row) {

            if (!is_array($row)) {
                CLI::error("Item #{$i} is not an object.");
                return;
            }

            foreach ($required as $key) {
                if (!array_key_exists($key, $row)) {
                    CLI::error("Missing required key '{$key}' in item #{$i}");
                    return;
                }
                if (!is_string($row[$key])) {
                    CLI::error("'{$key}' must be string in item #{$i}");
                    return;
                }
            }
        }

        // =============================
        // 3) Insert to database
        // =============================
        $db = Database::connect();

        if (!$db->tableExists('memberships')) {
            CLI::error("Table 'memberships' not found.");
            return;
        }

        CLI::write("Clearing table...", 'yellow');
        $db->table('memberships')->truncate();

        CLI::write("Inserting data...", 'yellow');
        $db->table('memberships')->insertBatch($data);

        CLI::write("Inserted membership rows: " . count($data), 'green');
    }
}