<?php

namespace App\Livewire\Admin\Concerns;

use Illuminate\Support\Facades\Route;

trait LoadsMenuOptions
{
    protected function loadRouteOptions(): array
    {
        return collect(Route::getRoutes())
            ->filter(fn ($route) => $route->getName())
            ->map(function ($route) {
                return [
                    'name' => $route->getName(),
                    'uri' => $route->uri(),
                    'methods' => implode('|', $route->methods()),
                ];
            })
            ->sortBy('name')
            ->values()
            ->all();
    }

    protected function loadModelOptions(): array
    {
        $models = [];
        $modelsPath = app_path('Models');

        if (! is_dir($modelsPath)) {
            return $models;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($modelsPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = str_replace(
                    [$modelsPath, '.php'],
                    ['', ''],
                    $file->getPathname()
                );

                $relativePath = str_replace(['/', '\\'], '\\', $relativePath);
                $relativePath = trim($relativePath, '\\');

                $className = 'App\\Models\\'.$relativePath;

                if (class_exists($className) && is_subclass_of($className, \Illuminate\Database\Eloquent\Model::class)) {
                    try {
                        $reflection = new \ReflectionClass($className);
                        if (! $reflection->isAbstract() && ! $reflection->isInterface()) {
                            $models[] = [
                                'class' => $className,
                                'name' => class_basename($className),
                            ];
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        return collect($models)
            ->sortBy('name')
            ->values()
            ->all();
    }
}
