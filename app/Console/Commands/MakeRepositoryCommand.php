<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new repository class';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $interfaceName = "{$name}RepositoryInterface";
        $className = "{$name}Repository";

        $repositoryDir = app_path('Repositories');
        $contractDir = app_path('Contracts');

        $repositoryPath = app_path("Repositories/{$className}.php");
        $interfacePath = app_path("Contracts/{$interfaceName}.php");

        if (!File::exists($repositoryDir)) {
            File::makeDirectory($repositoryDir, 0755, true, true);
            $this->info("Created directory: {$repositoryDir}");
        }

        if (!File::exists($contractDir)) {
            File::makeDirectory($contractDir, 0755, true, true);
            $this->info("Created directory: {$contractDir}");
        }

        if (!File::exists($interfacePath)) {
            File::ensureDirectoryExists($contractDir);
            File::put($interfacePath, $this->getInterfaceTemplate($interfaceName));
            $this->info("Created: {$interfacePath}");
        } else {
            $this->warn("Interface already exists: {$interfacePath}");
        }

        if (!File::exists($repositoryPath)) {
            File::ensureDirectoryExists($repositoryDir);
            File::put($repositoryPath, $this->getRepositoryTemplate($className, $interfaceName));
            $this->info("Created: {$repositoryPath}");
        } else {
            $this->warn("Repository already exists: {$repositoryPath}");
        }

        $this->bindInServiceProvider($interfaceName, $className);
    }

    protected function getInterfaceTemplate($interfaceName)
    {
        return <<<PHP
        <?php

        namespace App\Contracts;

        interface {$interfaceName}
        {
           //
        }
        PHP;
    }

    protected function getRepositoryTemplate($className, $interfaceName)
    {
        return <<<PHP
        <?php

        namespace App\Repositories;

        use App\Contracts\\{$interfaceName};

        class {$className} implements {$interfaceName}
        {
            //
        }
        PHP;
    }

    protected function bindInServiceProvider($interfaceName, $className)
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');

        if (!File::exists($providerPath)) {
            $this->error('AppServiceProvider.php not found.');
            return;
        }

        $content = File::get($providerPath);
        $interfaceFQN = "App\\Contracts\\{$interfaceName}";
        $classFQN = "App\\Repositories\\{$className}";
        $bindingLine = "\$this->app->bind(\\{$interfaceFQN}::class, \\{$classFQN}::class);";

        // Tambahkan use statement jika belum ada
        if (!str_contains($content, "use {$interfaceFQN};")) {
            $content = preg_replace(
                '/namespace App\\\Providers;(\s+)/',
                "namespace App\Providers;\n\nuse {$interfaceFQN};\nuse {$classFQN};$1",
                $content
            );
        }

        // Tambahkan binding di method register
        if (!str_contains($content, $bindingLine)) {
            $content = preg_replace(
                '/public function register\(\): void\s*\{/',
                "public function register(): void\n    {\n        {$bindingLine}",
                $content
            );
            File::put($providerPath, $content);
            $this->info("Binding added to AppServiceProvider.");
        } else {
            $this->warn("Binding already exists in AppServiceProvider.");
        }
    }
}
