<?php

namespace Donorservices\FilamentActionWizard\Tasks;

use function Laravel\Prompts\select;
class IconSelectionTask
{

    public function selectIcon(): string
    {
        return select(
            label: 'What icon should the action have?',
            options: [
                'heroicon-o-star' => 'Star',
                'heroicon-o-edit' => 'Edit',
            ],
            default: 'heroicon-o-star'
        );
    }
}
