<?php

namespace Donorservices\FilamentActionWizard\Tasks;

use Illuminate\Support\Str;
use function Laravel\Prompts\text;
use function Laravel\Prompts\error;
use function Laravel\Prompts\warning;
class ActionLabelTask
{
    public function getActionLabel(): string
    {
        return text('Enter the action label (e.g., Activate User):');
    }

    public function processActionLabel($actionLabel): array
    {
        // Clean the input for ACTION_LABEL (only letters and spaces)
        $cleanLabel = preg_replace('/[^a-zA-Z\s]/', '', $actionLabel);
        $cleanLabel = ucwords(strtolower($cleanLabel)); // Capitalize each word

        // Convert for ACTION_NAME (lowercase, replace spaces with underscores)
        $actionName = strtolower(str_replace(' ', '_', $cleanLabel));

        // Create CLASS_NAME (remove spaces, capitalize each word, append 'BaseAction')
        $classNameParts = explode(' ', $cleanLabel);
        $className = implode('', array_map('ucfirst', $classNameParts)) . 'BaseAction';

        return [$cleanLabel, $actionName, $className];
    }
}
