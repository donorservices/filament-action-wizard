<?php

namespace Donorservices\FilamentActionWizard\Tasks;

use function Laravel\Prompts\confirm;
class UseNotificationInputTask
{

    public function confirmNotificationRequirement()
    {
        return confirm(
            label: 'Does your action need success and failure notifications?',
            default: false,
            yes: 'Yes',
            no: 'No'
        );
    }
}
