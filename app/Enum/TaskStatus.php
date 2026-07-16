<?php

declare(strict_types=1);

namespace App\Enum;

enum TaskStatus: string
{
    case DONE = 'DONE';

    case NOT_DONE = 'NOT_DONE';
}
