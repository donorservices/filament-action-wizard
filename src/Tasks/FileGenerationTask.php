<?php

namespace Donorservices\FilamentActionWizard\Tasks;

use Illuminate\Support\Str;
use function Laravel\Prompts\info;
class FileGenerationTask
{

    public function generateFiles($model, $namespace, $modelUseStatement, $className, $actionLabel, $actionName, $iconName, $formInput, $notifyEnabled): void
    {
        $stubTypes = ['base_action', 'page_action', 'table_action'];
        foreach ($stubTypes as $stubType) {
            $content = $this->processStub($stubType, $model, $namespace, $modelUseStatement, $className, $actionLabel, $actionName, $iconName, $formInput, $notifyEnabled);
            $this->saveFile($content, $model, $className, $stubType);
        }
    }

    private function processStub($stubType, $model, $namespace, $modelUseStatement, $className, $actionLabel, $actionName, $iconName, $formInput, $notifyEnabled): array|bool|string
    {
        $stubPath = __DIR__ . "/../stubs/{$stubType}.stub";
        $stub = file_get_contents($stubPath);

        // Replace placeholders in the stub
        $formDataAssign = $formInput ? ', array $data' : '';
        if (!$formInput) {
            // Remove the ->form() line
            $stub = preg_replace('/\s*->form\(\[\s*(\/\/\s*TextInput::make\(\)\s*)?\]\)/', '', $stub);
        }

        $classNameWithoutBaseAction = Str::replaceLast('BaseAction', '', $className);
        if ($stubType === 'page_action') {
            $classNameWithoutBaseAction .= 'Action';
        } elseif ($stubType === 'table_action') {
            $classNameWithoutBaseAction .= 'TableAction';
        }

        $notificationMethods = $notifyEnabled ?
        "protected static function successNotification() {
        Notification::make()
            ->title('Success')
            ->success()
            ->send();
    }

    protected static function failureNotification() {
        Notification::make()
            ->title('Failure')
            ->error()
            ->send();
    }" : "";

        $successNotificationMethodCall = $notifyEnabled ? "self::successNotification()" : "";
        $failureNotificationMethodCall = $notifyEnabled ? "self::failureNotification()" : "";

        $useNotifications = $notifyEnabled ? "use Filament\Notifications\Notification;" : "";


        $replacements = [
            '{{CLASS_NAME_WITHOUT_BASEACTION}}' => $classNameWithoutBaseAction,
            '{{NAMESPACE}}' => $namespace,
            '{{MODEL_USE_STATEMENT}}' => $modelUseStatement,
            '{{USE_NOTIFICATIONS}}' => $useNotifications,
            '{{NOTIFICATION_METHODS}}' => $notificationMethods,
            '{{SUCCESS_NOTIFICATION_METHOD_CALL}}' => $successNotificationMethodCall,
            '{{FAILURE_NOTIFICATION_METHOD_CALL}}' => $failureNotificationMethodCall,
            '{{CLASS_NAME}}' => $className,
            '{{ACTION_LABEL}}' => $actionLabel,
            '{{ACTION_NAME}}' => $actionName,
            '{{ICON_NAME}}' => $iconName,
            '{{MODEL_CLASS}}' => $model,
            '{{FORM_DATA_ASSIGN}}' => $formDataAssign
        ];

        foreach ($replacements as $placeholder => $value) {
            $stub = str_replace($placeholder, $value, $stub);
        }

        return $stub;
    }

    private function saveFile($content, $model, $className, $stubType): void
    {
        $directory = base_path("app/Filament/Actions/{$model}");
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $fileName = $className . '.php';
        if ($stubType !== 'base_action') {
            $fileName = Str::replaceLast('BaseAction', '', $className) . ($stubType === 'page_action' ? 'Action' : 'TableAction') . '.php';
        }

        $filePath = "{$directory}/{$fileName}";
        file_put_contents($filePath, $content);
        info("File generated at: {$filePath}");
    }

}
