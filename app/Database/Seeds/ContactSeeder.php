<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\CLI\CLI;
use Config\Database;
use JsonException;

class ContactSeeder extends Seeder
{
    protected string $jsonPath = APPPATH . 'Database/Seeds/json/non-pages/contact.json';

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
            CLI::error("contact.json must be an ARRAY of objects.");
            return;
        }

        $required = ['name', 'email', 'subject', 'message'];

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

        if (!$db->tableExists('contact')) {
            CLI::error("Table 'contact' not found.");
            return;
        }

        CLI::write("Clearing table...", 'yellow');
        $db->table('contact')->truncate();

        CLI::write("Inserting data...", 'yellow');
        $db->table('contact')->insertBatch($data);

        CLI::write("Inserted contact rows: " . count($data), 'green');
    }
}