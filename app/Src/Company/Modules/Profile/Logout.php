<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Profile;

use App\Http\Controllers\Controller;
use App\Utility\Traits\AuthenticateTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Logout extends Controller
{
    use AuthenticateTrait;

    const guard = 'company';

    public function __invoke(Request $request): RedirectResponse
    {
        $this->sessionLogout($request);
        // flashAlert(__('company.logout.to_login'), 'success');

        return redirect()->route('company.auth.login');
    }
}
