<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $className = "{$name}Service";
        $serviceDir = app_path('Services');
        $servicePath = "{$serviceDir}/{$className}.php";

        if (!File::exists($serviceDir)) {
            File::makeDirectory($serviceDir, 0755, true);
            $this->info("Created directory: {$serviceDir}");
        }

        if (!File::exists($servicePath)) {
            File::ensureDirectoryExists($serviceDir);
            File::put($servicePath, $this->getServiceTemplate($className));
            $this->info("Created: {$servicePath}");
        } else {
            $this->warn("Service already exists: {$servicePath}");
        }
    }

    protected function getServiceTemplate($className)
    {
        return <<<PHP
        <?php

        namespace App\Services;

        class {$className}
        {
            public function __construct()
            {
                //
            }
        }
        PHP;
    }
}
