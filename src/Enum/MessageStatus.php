<?php

namespace App\Enum;

enum MessageStatus: string
{
    case NEW = 'new';
    case READ = 'read';
    case ARCHIVED = 'archived';
}