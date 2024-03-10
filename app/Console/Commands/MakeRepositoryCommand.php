<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends Command
{
  protected $signature = 'make:repository {name}';
  protected $description = 'Create a new repository class and interface';

  public function handle()
  {
    $name = $this->argument('name');

    $repositoryPath = $this->getRepositoryPath($name);
    $interfacePath = $this->getInterfacePath($name);

    if (file_exists($repositoryPath) || file_exists($interfacePath)) {
      $this->error("Repository or interface with name '$name' already exists!");
      return 1;
    }

    $this->createDirectory(dirname($repositoryPath)); // Create directory for both files

    $this->createClass($repositoryPath, $name);
    $this->createInterface($interfacePath, $name);

    $this->info("Repository created successfully!");
    return 0;
  }

  protected function getRepositoryPath(string $name): string
  {
    return app_path('Repository') . '/' . Str::studly($name) . '.php';
  }

  protected function getInterfacePath(string $name): string
  {
    return app_path('Repository') . '/' . 'I' . Str::studly($name) . '.php';
  }

  protected function createDirectory(string $directory): void
  {
    if (!is_dir($directory)) {
      mkdir($directory, 0755, true);
    }
  }

  protected function createClass(string $path, string $name): void
  {
    $stub = file_get_contents(app_path('stubs/repository.stub'));
    // Replace placeholder in the stub content
    $class = str_replace('{{ class }}', $name, $stub);
    file_put_contents($path, $class);
  }

  protected function createInterface(string $path, string $name): void
  {
    $stub = file_get_contents(app_path('stubs/repository.interface.stub'));
    // Replace placeholder in the stub content
    $interface = str_replace('{{ interface }}', $name, $stub);
    file_put_contents($path, $interface);
  }

  // ... removed unused methods (getArguments)
}
