<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\CLI\CLI;
use Config\Database;
use JsonException;

class QuoteSeeder extends Seeder
{
    protected string $jsonPath = APPPATH . 'Database/Seeds/json/non-pages/quote.json';

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
            CLI::error("quote.json must be an ARRAY of objects.");
            return;
        }

        $required = ['author', 'role', 'text'];

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

        if (!$db->tableExists('quote')) {
            CLI::error("Table 'quote' not found.");
            return;
        }

        CLI::write("Clearing table...", 'yellow');
        $db->table('quote')->truncate();

        CLI::write("Inserting data...", 'yellow');
        $db->table('quote')->insertBatch($data);

        CLI::write("Inserted quote rows: " . count($data), 'green');
    }
}