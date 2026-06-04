<?php

declare(strict_types=1);

namespace App\Src\Admin\Modules\Profile;

use App\Http\Controllers\Controller;
use App\Utility\Traits\AuthenticateTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Logout extends Controller
{
    use AuthenticateTrait;

    const guard = 'moderator';

    public function __invoke(Request $request): RedirectResponse
    {
        $this->sessionLogout($request);
        flashAlert(__('admin.logout.to_login'), 'success');

        return redirect()->route('admin.auth.login');
    }
}
