<?php

namespace VanDmade\Cuztomisable\Controllers;

use Illuminate\Http\Request;
use Exception;

class SettingsController extends Controller
{

    public function all()
    {
        try {
            // All settings used within the frontend will need to be returned here
            return $this->success([
                'login_with' => config('cuztomisable.login.login_with', []),
                'remember' => config('cuztomisable.login.remember', False),
                'multi_factor_authentication' => [
                    'resend_after' => config('cuztomisable.login.multi_factor_authentication.resend_after', 300),
                    'send_via' => config('cuztomisable.login.multi_factor_authentication.send_via', ['email' => true]),
                ],
                'session_length' => config('cuztomisable.login.session_length', 300),
                'verification' => config('cuztomisable.login.verification', []),
                'passwords' => [
                    'reset_with' => config('cuztomisable.account.passwords.reset_with', ['email' => true]),
                    'time_between_allowed_resets' => config('cuztomisable.account.passwords.time_between_allowed_resets', 900),
                    'resend_after' => config('cuztomisable.account.passwords.resend_after', 300),
                    'send_via' => config('cuztomisable.account.passwords.send_via', ['email' => true]),
                    'requirements' => config('cuztomisable.account.passwords.requirements', []),
                ],
            ]);
        } catch (Exception $error) {
            return $this->error($error);
        }
    }

}