<?php

namespace Donorservices\FilamentActionWizard\Tasks;

use Illuminate\Support\Str;
use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\search;
class ModelInputTask
{
    public function getModelInput(): bool|int|string
    {
        // Confirm if the model is in a custom directory
        $checkModelPathCustom = confirm(
            label: 'Is the targeted model in the standard directory App/Models?',
            default: true,
            yes: 'Yes',
            no: 'No'
        );

        if (!$checkModelPathCustom) {
            $customModelPath = confirm(
                label: 'Is the targeted model in the Modules directory?',
                default: true,
                yes: 'Yes',
                no: 'No'
            );
            
        }

        if (!$checkModelPathCustom) {
            if (!$customModelPath) {
                error('Nope!');
                return false;
            } else {
                error('Yep!');
                $allModels = $this->getModulesModels();
            }
            
        } else {
            $allModels = $this->getAllModels();
        }

        if (empty($allModels)) {
            error('No models found in the specified directory.');
            return false;
        }

        return search(
            'Search for the model that will be targeted by action',
            fn (string $value) => strlen($value) > 0
                ? array_filter($allModels, fn($model) => stripos($model, $value) !== false)
                : []
        );
    }

    public function processModelInput($modelClass): bool|array
    {
        $model = class_basename($modelClass);
        $namespace = "App\\Filament\\Actions\\{$model}";
        $modelUseStatement = "use {$modelClass};";
        ray($model)->label('Final Model');
        ray($namespace)->label('Namespace');
        ray($modelUseStatement)->label('Use Model Statement');

        return [$model, $modelClass, $namespace, $modelUseStatement];
    }
    private function getAllModels($customPath = null): array
    {
        $models = [];
        $modelsPath = $customPath ? base_path($customPath) : app_path('Models');

        if (file_exists($modelsPath)) {
            foreach (scandir($modelsPath) as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    $modelName = pathinfo($file, PATHINFO_FILENAME);
                    $models[] = $customPath ? "{$customPath}\\{$modelName}" : "App\\Models\\{$modelName}";
                }
            }
        }

        return $models;
    }

    private function getModulesModels($customPath = null): array
    {
        $models = [];
        $modelsPath = $customPath ? base_path($customPath) : app_path('Modules');

        if (file_exists($modelsPath)) {
            foreach (scandir($modelsPath) as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    $modelName = pathinfo($file, PATHINFO_FILENAME);
                    $models[] = $customPath ? "{$customPath}\\{$modelName}" : "\\{$modelName}";
                }
            }
        }

        return $models;
    }

}
