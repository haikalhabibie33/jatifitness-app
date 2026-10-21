<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Services;

class DbRebuild extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:rebuild';
    protected $description = 'Dev-only: regenerate migrations from templates, optionally purge old, clean history, migrate, and seed.';

    protected $usage = 'db:rebuild [options]';
    protected $options = [
        '--dry'            => 'Preview only; do not write or execute.',
        '--purge'          => 'Delete existing migration files (default).',
        '--keep-old'       => 'Keep existing migration files (conflicts with --purge).',
        '--templates'      => 'Glob of template files to copy (default: Create*.php)',
        '--templates-dir'  => 'Templates directory (default: APPPATH/Database/MigrationTemplates)',
        '--namespace'      => 'Namespace (default: App)',
        '--dir'            => 'Migrations directory (default: APPPATH/Database/Migrations)',
        '--clean-history'  => 'Truncate the migrations table before migrate.',
        '--migrate'        => 'Run migrate after regenerating (default: 1). Set 0 to skip.',
        '--migrate-args'   => 'Extra args for migrate command (e.g. "--all")',
        '--seed'           => 'Run seeder after migrate (default: 1). Set 0 to skip.',
        '--seeder'         => 'Seeder class (default: PageSettingsSeeder)',
        '--seed-args'      => 'Extra args for seeder (e.g. "--strict=1")',
        '--truncate'       => 'Comma-separated tables to TRUNCATE before seeding (e.g. "page_settings,services_items")',
    ];

    public function run(array $params)
    {
        // ---- Guard: dev/testing only
        $env = env('CI_ENVIRONMENT') ?? 'production';
        if (! in_array($env, ['development', 'testing'], true)) {
            CLI::error("Refused. db:rebuild is for development/testing only. Current: {$env}");
            return;
        }

        // ---- Options
        $dry           = (bool) CLI::getOption('dry');
        $purge         = (bool) CLI::getOption('purge');
        $keepOld       = (bool) CLI::getOption('keep-old');
        $namespace     = CLI::getOption('namespace')     ?? 'App';
        $migrationsDir = rtrim(CLI::getOption('dir')     ?? APPPATH . 'Database/Migrations', '/\\') . DIRECTORY_SEPARATOR;
        $templatesDir  = rtrim(CLI::getOption('templates-dir') ?? APPPATH . 'Database/MigrationTemplates', '/\\') . DIRECTORY_SEPARATOR;
        $templatesGlob = CLI::getOption('templates')     ?? 'Create*.php';
        $cleanHistory  = (bool) CLI::getOption('clean-history');

        $runMigrate    = CLI::getOption('migrate');
        $runMigrate    = $runMigrate === null ? true : (bool) $runMigrate;
        $migrateArgs   = CLI::getOption('migrate-args')  ?? '';

        $runSeed       = CLI::getOption('seed');
        $runSeed       = $runSeed === null ? true : (bool) $runSeed;
        $seederClass   = CLI::getOption('seeder')        ?? 'DatabaseSeeder';
        $seedArgs      = CLI::getOption('seed-args')     ?? '';

        $truncate      = CLI::getOption('truncate');
        $tablesToTrunc = $truncate ? array_filter(array_map('trim', explode(',', $truncate))) : [];

        if ($purge && $keepOld) {
            CLI::error('Options conflict: use either --purge or --keep-old, not both.');
            return;
        }

        // ---- Preview header
        CLI::write('=== DB REBUILD (dev) ===', 'yellow');
        CLI::write("Env            : {$env}");
        CLI::write("Migrations dir : {$migrationsDir}");
        CLI::write("Templates dir  : {$templatesDir}");
        CLI::write("Templates glob : {$templatesGlob}");
        CLI::write("Purge old      : " . ($purge ? 'yes' : 'no'));
        CLI::write("Keep old       : " . ($keepOld ? 'yes' : 'no'));
        CLI::write("Clean history  : " . ($cleanHistory ? 'yes' : 'no'));
        CLI::write("Migrate        : " . ($runMigrate ? 'yes' : 'no') . ' ' . $migrateArgs);
        CLI::write("Seed           : " . ($runSeed ? 'yes' : 'no') . ' ' . $seedArgs . " [{$seederClass}]");
        if ($tablesToTrunc) {
            CLI::write('Truncate       : ' . implode(', ', $tablesToTrunc));
        }
        if ($dry) {
            CLI::write('DRY-RUN        : yes (no changes will be applied)', 'yellow');
        }
        CLI::newLine();

        // ---- Validate dirs
        if (! is_dir($migrationsDir)) {
            CLI::error("Migrations directory not found: {$migrationsDir}");
            return;
        }
        if (! is_dir($templatesDir)) {
            CLI::error("Templates directory not found: {$templatesDir}");
            return;
        }

        // ---- Collect templates
        $templates = glob($templatesDir . $templatesGlob) ?: [];
        if (empty($templates)) {
            CLI::error("No template matched: {$templatesDir}{$templatesGlob}");
            return;
        }

        // ---- Purge or keep old migrations
        if ($purge) {
            CLI::write('Cleaning old migration files...', 'yellow');
            foreach (glob($migrationsDir . '*.php') ?: [] as $file) {
                CLI::write(' - delete ' . basename($file));
                if (! $dry) {
                    @unlink($file);
                }
            }
        } else {
            CLI::write('Keeping existing migration files.', 'yellow');
        }

        // ---- Generate new migration(s) from templates
        $stamp = date('Y-m-d-His');
        $seq   = 0;

        CLI::write('Generating new migration(s)...', 'yellow');
        foreach ($templates as $tpl) {
            $base = pathinfo($tpl, PATHINFO_FILENAME);       // e.g. CreatePageSettingsTable
            $ts   = $stamp;
            if ($seq > 0) {
                // pastikan urut kalau lebih dari satu file dalam detik yang sama
                $ts = date('Y-m-d-His', time() + $seq);
            }
            $new  = $migrationsDir . "{$ts}_{$base}.php";

            CLI::write(' - ' . basename($new));
            if (! $dry) {
                if (! copy($tpl, $new)) {
                    CLI::error('   ! failed to copy: ' . basename($tpl));
                }
            }
            $seq++;
        }

        // ---- Clean migrations history table if requested
        if ($cleanHistory) {
            CLI::write('Truncating migrations table...', 'yellow');
            if (! $dry) {
                $db = db_connect();
                $db->table('migrations')->truncate();
            }
        }

        // ---- Migrate
        if ($runMigrate) {
            CLI::write('Running migrations...', 'yellow');
            if (! $dry) {
                // pakai MigrationRunner service agar berada di proses yang sama
                $runner = Services::migrations();
                $runner->setNamespace($namespace);
                try {
                    // Jika perlu semua namespace: $runner->latest('all');
                    // Di sini setNamespace sudah cukup untuk App
                    $runner->latest();
                    CLI::write('Migrate finished.', 'green');
                } catch (\Throwable $e) {
                    CLI::error('Migrate failed: ' . $e->getMessage());
                    return;
                }
            }
        }

        // ---- Optional truncate specific tables
        if ($tablesToTrunc) {
            CLI::write('Truncating tables before seeding...', 'yellow');
            if (! $dry) {
                $db = db_connect();
                $db->query('SET FOREIGN_KEY_CHECKS=0');
                foreach ($tablesToTrunc as $t) {
                    if ($db->tableExists($t)) {
                        $db->table($t)->truncate();
                        CLI::write(" - truncated {$t}");
                    } else {
                        CLI::write(" - skip (table not found): {$t}", 'yellow');
                    }
                }
                $db->query('SET FOREIGN_KEY_CHECKS=1');
            }
        }

        // ---- Seed
        if ($runSeed) {
            CLI::write("Running seeder: {$seederClass} {$seedArgs}", 'yellow');
            if (! $dry) {
                try {
                    // gunakan command() agar seeder menerima args
                    command('db:seed ' . $seederClass . ' ' . $seedArgs);
                } catch (\Throwable $e) {
                    CLI::error('Seeder failed: ' . $e->getMessage());
                    return;
                }
                CLI::write('Seeding finished.', 'green');
            }
        }

        CLI::write('Database rebuild completed.', 'green');
    }
}