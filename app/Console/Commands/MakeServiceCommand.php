<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class MakeServiceCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'make:service {name}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create a new service class';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $name = $this->argument('name');

    $path = $this->getPath($name);

    if (file_exists($path)) {
      $this->error("Service $name already exists!");
      return 1;
    }

    $this->createDirectory(dirname($path));
    $this->createClass($path, $name);

    $this->info("Service created successfully!");
    return 0;
  }

  protected function getPath(string $name): string
  {
    $dtoPath = app_path('Services');
    $className = Str::studly($name);
    return "$dtoPath/$className.php";
  }

  protected function createDirectory(string $directory): void
  {
    if (!is_dir($directory)) {
      mkdir($directory, 0755, true);
    }
  }

  protected function createClass(string $path, string $name): void
  {
    $stub = $this->getStub();
    $class = str_replace('{{ class }}', $name, $stub);
    file_put_contents($path, $class);
  }

  protected function getStub(): string
  {
    $stubPath = app_path('stubs/service.stub');
    return file_get_contents($stubPath);
  }

  protected function getArguments(): array
  {
    return [
      [
        'name',
        InputArgument::REQUIRED,
        'The name of the service class.',
      ],
    ];
  }
}
