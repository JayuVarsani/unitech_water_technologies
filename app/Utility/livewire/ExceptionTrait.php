<?php

declare(strict_types=1);

namespace App\Utility\livewire;

use Exception;

trait ExceptionTrait
{
    /**
     * @throws Exception
     */
    public function exception(Exception $e, $stopPropagation): void
    {
        $stopPropagation();
        flashAlert($e->getMessage(), 'danger');
        $this->redirect(redirect()->back()->getTargetUrl());
    }
}
