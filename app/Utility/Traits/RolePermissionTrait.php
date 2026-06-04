<?php

declare(strict_types=1);

namespace App\Utility\Traits;

trait RolePermissionTrait
{
    public function hasPermission($moduleUniqueName, $type, $abort = true): bool
    {
        $action = $moduleUniqueName.'.'.$type;

        if ($this->can($action)) {
            return true;
        }

        return false;
    }
}
