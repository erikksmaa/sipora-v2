<?php

namespace App\Enums;

enum SystemRole: string
{
    case Youth = 'youth';
    case Verifier = 'verifier';
    case Admin = 'admin';
}
