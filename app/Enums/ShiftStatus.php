<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

namespace App\Enums;

enum ShiftStatus: string
{
    case ShiftStart = 'shift_start';
    case ShiftEnd = 'shift_end';
    case ShiftAbort = 'shift_abort';
    case BreakStart = 'break_start';
    case BreakEnd = 'break_end';
    case BreakAbort = 'break_abort';
}
