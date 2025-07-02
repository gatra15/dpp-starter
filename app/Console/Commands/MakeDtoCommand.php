<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDtoCommand extends Command
{
    protected $signature = 'make:dto {name}';
    protected $description = 'Create a new DTO class';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $className = "{$name}Dto";
        $dtoDir = app_path('DTOs');
        $dtoPath = "{$dtoDir}/{$className}.php";

        if (!File::exists($dtoDir)) {
            File::makeDirectory($dtoDir, 0755, true);
            $this->info("Created directory: {$dtoDir}");
        }

        if (!File::exists($dtoPath)) {
            File::ensureDirectoryExists($dtoDir);
            File::put($dtoPath, $this->getDtoTemplate($className));
            $this->info("Created: {$dtoPath}");
        } else {
            $this->warn("DTO already exists: {$dtoPath}");
        }
    }

    protected function getDtoTemplate($className)
    {
        return <<<PHP
        <?php

        namespace App\DTOs;

        class {$className}
        {
            // public string \$name;
            // public int \$age;

            public function __construct(array \$data)
            {
                // \$this->name = \$data['name'];
            }
        }
        PHP;
    }
}
