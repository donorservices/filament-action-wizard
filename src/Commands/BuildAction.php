<?php

namespace Donorservices\FilamentActionWizard\Commands;

use Donorservices\FilamentActionWizard\Tasks\ActionLabelTask;
use Donorservices\FilamentActionWizard\Tasks\FileGenerationTask;
use Donorservices\FilamentActionWizard\Tasks\FormInputTask;
use Donorservices\FilamentActionWizard\Tasks\IconSelectionTask;
use Donorservices\FilamentActionWizard\Tasks\ModelInputTask;
use Donorservices\FilamentActionWizard\Tasks\UseNotificationInputTask;
use Illuminate\Console\Command;


class BuildAction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-action:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wizard style interface for generating a FilamentPHP Action group that can be used for table, page and bulk';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        
        // Get target directory from user input
        $modelInputTask = new ModelInputTask();
        $modelInput = $modelInputTask->getModelInput();
        if (!$modelInput) {
            // Handle error
            return 0;
        }
        $processedModelInput = $modelInputTask->processModelInput($modelInput);
        [$model, $modelClass, $namespace, $modelUseStatement] = $processedModelInput;

        $formInputTask = new FormInputTask();
        $formInput = $formInputTask->confirmFormRequirement();

        $notifyEnabledInputTask = new UseNotificationInputTask();
        $notifyEnabled = $notifyEnabledInputTask->confirmNotificationRequirement();

        $actionLabelTask = new ActionLabelTask();
        $actionLabel = $actionLabelTask->getActionLabel();
        [$cleanLabel, $actionName, $className] = $actionLabelTask->processActionLabel($actionLabel);

        $iconSelectionTask = new IconSelectionTask();
        $iconName = $iconSelectionTask->selectIcon();

        $fileGenerationTask = new FileGenerationTask();
        $fileGenerationTask->generateFiles($model, $namespace, $modelUseStatement, $className, $actionLabel, $actionName, $iconName, $formInput, $notifyEnabled);
    }


}
