<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\CLI\CLI;
use Config\Database;
use JsonException;

class UserSeeder extends Seeder
{
    protected string $jsonPath = APPPATH . 'Database/Seeds/json/non-pages/users.json';

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
        // 2) Validation
        // =============================
        if (!is_array($data)) {
            CLI::error("users.json must be an ARRAY of objects.");
            return;
        }

        $required = ['name', 'username', 'password', 'email', 'phone'];

        foreach ($data as $i => $row) {
            if (!is_array($row)) {
                CLI::error("Item #{$i} is not an object.");
                return;
            }

            foreach ($required as $key) {
                if (!array_key_exists($key, $row) || !is_string($row[$key])) {
                    CLI::error("Invalid or missing '{$key}' in item #{$i}");
                    return;
                }
            }
        }

        // =============================
        // 3) Hash password + timestamps
        // =============================
        $now = date('Y-m-d H:i:s');
        CLI::write("Hashing passwords & adding timestamps...", 'yellow');

        foreach ($data as &$row) {
            $row['password']   = password_hash($row['password'], PASSWORD_DEFAULT);
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }
        unset($row);

        // =============================
        // 4) Insert to database
        // =============================
        $db = Database::connect();

        if (!in_array('users', $db->listTables(), true)) {
            CLI::error("Table 'users' not found.");
            return;
        }

        CLI::write("Clearing table...", 'yellow');
        $db->table('users')->truncate();

        CLI::write("Inserting data...", 'yellow');
        $db->table('users')->insertBatch($data);

        CLI::write("Inserted user rows: " . count($data), 'green');
    }
}
