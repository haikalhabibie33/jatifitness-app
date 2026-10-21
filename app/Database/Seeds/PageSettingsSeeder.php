<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\CLI\CLI;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Opis\JsonSchema\Validator;
use JsonException;

class PageSettingsSeeder extends Seeder
{
    protected string $jsonDir = APPPATH . 'Database/Seeds/json/pages';
    protected string $schemaDir = APPPATH . 'Database/Seeds/schema/pages';
    protected string $defaultSchema = APPPATH . 'Database/Seeds/schema/pages/page-settings.schema.json';

    public function run()
    {
        // ===== KONFIG (bisa override via CLI option) =====
        $jsonDir       = rtrim(CLI::getOption('dir') ?? $this->jsonDir, '/\\');
        $schemaDir     = rtrim(CLI::getOption('schemaDir') ?? $this->schemaDir, '/\\');
        $defaultSchema = rtrim(CLI::getOption('defaultSchema') ?? $this->defaultSchema, '/\\');

        if (!is_dir($jsonDir)) {
            throw new InvalidArgumentException("JSON directory not found: {$jsonDir}");
        }

        if (!is_dir($schemaDir)) {
            throw new InvalidArgumentException("Schema directory not found: {$schemaDir}");
        }

        if (!is_file($defaultSchema)) {
            throw new InvalidArgumentException("Default schema not found: {$defaultSchema}");
        }

        // ===== LOAD DEFAULT SCHEMA =====
        $defaultSchemaObj = $this->loadSchemaObject($defaultSchema);

        // ===== PREPARE VALIDATOR & FILE FINDER =====
        $validator = new Validator();

        $finder = new Finder();
        $finder->files()->in($jsonDir)->name('*.json')->sortByName();

        if (!$finder->hasResults()) {
            CLI::write("No JSON files in {$jsonDir}", 'yellow');
            return;
        }

        $now = date('Y-m-d H:i:s');

        // ===== PROCESS EACH JSON FILE =====
        foreach ($finder as $fi) {

            $page = pathinfo($fi->getFilename(), PATHINFO_FILENAME);
            $path = $fi->getRealPath();

            // ===== DECODE JSON =====
            try {
                $data = json_decode($fi->getContents(), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                CLI::error("Invalid JSON in {$path}: " . $e->getMessage());
                continue;
            }

            if (!is_array($data)) {
                CLI::error("Root must be JSON object in {$path}");
                continue;
            }

            // ===== SELECT SCHEMA =====
            $schemaPath = $schemaDir . DIRECTORY_SEPARATOR . $page . '.schema.json';

            $schemaObj = is_file($schemaPath)
                ? $this->loadSchemaObject($schemaPath)
                : $defaultSchemaObj;

            // ===== VALIDATE JSON =====
            $result = $validator->validate((object) $data, $schemaObj);

            if (!$result->isValid()) {

                CLI::error("Schema validation failed for '{$page}'");

                $error = $result->error();

                if ($error) {
                    CLI::error(
                        "  - [" . $error->keyword() . "] " . $error->message()
                    );
                }

                continue;
            }

            // ===== UPSERT DATABASE =====
            $payload = [
                'settings_json' => json_encode(
                    $data,
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                ),
                'updatedAt' => $now,
            ];

            $table = $this->db->table('page_settings');

            $exists = $table
                ->select('id')
                ->where('page', $page)
                ->get()
                ->getFirstRow('array');

            if ($exists) {

                $table
                    ->where('page', $page)
                    ->update($payload);

                CLI::write("Updated page: {$page}", 'cyan');

            } else {

                $table->insert(array_merge(
                    ['page' => $page],
                    $payload
                ));

                CLI::write("Inserted page: {$page}", 'green');
            }
        }

        CLI::write('Done.', 'green');
    }

    protected function loadSchemaObject(string $schemaPath): object
    {
        try {
            $json = file_get_contents($schemaPath);

            if ($json === false) {
                throw new RuntimeException("Cannot read schema: {$schemaPath}");
            }

            return json_decode($json, false, 512, JSON_THROW_ON_ERROR);

        } catch (JsonException $e) {
            throw new RuntimeException("Invalid schema JSON: {$schemaPath}");
        }
    }
}