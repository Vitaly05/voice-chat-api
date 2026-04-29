<?php

namespace App\Enums;

enum ChatStatuses: string
{
    case REQUESTED = 'requested';

    case ACCEPTED = 'accepted';

    case DECLINED = 'declined';

    case REMOVED = 'removed';
}
