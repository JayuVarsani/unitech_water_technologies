<?php

declare(strict_types=1);

namespace App\Utility\Traits;

trait FormSessionTrait
{
    public function mountFormSessionTrait(): void
    {
        $prevData = $this->getFormSession();
        if ($prevData) {
            $this->form = unserialize($prevData);
        }
    }

    public function getFormSession()
    {
        return session()->get($this->formSessionName);
    }

    public function storeFromSession(): void
    {
        session()->put($this->formSessionName, serialize($this->form));
    }

    public function purgeFromSession(): void
    {
        session()->forget($this->formSessionName);
    }
}
