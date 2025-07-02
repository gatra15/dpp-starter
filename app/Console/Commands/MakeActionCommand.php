<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeActionCommand extends Command
{
    protected $signature = 'make:action {name}';
    protected $description = 'Create a new Action class';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $className = "{$name}Action";
        $actionDir = app_path('Actions');
        $actionPath = "{$actionDir}/{$className}.php";

        if (!File::exists($actionDir)) {
            File::makeDirectory($actionDir, 0755, true);
            $this->info("Created directory: {$actionDir}");
        }

        if (!File::exists($actionPath)) {
            File::ensureDirectoryExists($actionDir);
            File::put($actionPath, $this->getActionTemplate($className));
            $this->info("Created: {$actionPath}");
        } else {
            $this->warn("Action already exists: {$actionPath}");
        }
    }

    protected function getActionTemplate($className)
    {
        return <<<PHP
        <?php

        namespace App\Actions;

        class {$className}
        {
            public function execute(array \$data): mixed
            {
                //
            }
        }
        PHP;
    }
}
