<?php

namespace Donorservices\FilamentActionWizard\Tasks;

use function Laravel\Prompts\confirm;
class FormInputTask
{

    public function confirmFormRequirement(): bool
    {
        return confirm(
            label: 'Does your action need a form?',
            default: false,
            yes: 'Yes',
            no: 'No'
        );
    }
}
