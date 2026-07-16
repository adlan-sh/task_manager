<?php

declare(strict_types=1);

namespace App\Enum;

enum TaskPriority: string
{
    case LOW = 'LOW';

    case MIDDLE = 'MIDDLE';

    case HIGH = 'HIGH';
}
