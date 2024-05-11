<?php

namespace VanDmade\Cuztomisable\Controllers\Authentication;

use VanDmade\Cuztomisable\Controllers\Controller;
use Illuminate\Http\Request;
use VanDmade\Cuztomisable\Requests\Authentication\MFA\MFARequest;
use VanDmade\Cuztomisable\Requests\Authentication\MFA\SendRequest;
use VanDmade\Cuztomisable\Requests\TableRequest;
use VanDmade\Cuztomisable\Mail\Authentication\MFA as MFAMail;
use VanDmade\Cuztomisable\Models\Users;
use Auth;
use DB;
use Exception;

class MFAController extends Controller
{

    public function send(SendRequest $request, $token)
    {
        try {
            $data = $request->validated();
            $code = Users\Code::where('token', '=', $token)
                ->whereHas('user')
                ->whereNull('used_at')
                ->where('expires_at', '>=', date('Y-m-d H:i:s'))
                ->first();
            // Makes sure the code exists, hasn't been used, and a user is attached
            if (!isset($code->id)) {
                throw new Exception(__('cuztomisable/authentication.mfa.errors.not_found'), 404);
            }
            $resendAfter = config('cuztomisable.login.multi_factor_authentication.resend_after', 300);
            $resending = is_null($code->sent_at) ? false : true;
            // Checks to see if the code was sent recently
            if (!is_null($code->sent_at) && strtotime('-'.$resendAfter.' seconds') < strtotime($code->sent_at)) {
                throw new Exception(__('cuztomisable/authentication.mfa.errors.sent_recently'), 401);
            }
            // Determines if the code needs to be recreated or not
            if (config('cuztomisable.login.multi_factor_authentication.recreate_code_on_resend', false)) {
                $code->code = generateCode(config('cuztomisable.account.code.length', 6), 'cuztomisable', $code->id);
            }
            $code->sent_at = date('Y-m-d H:i:s');
            $sendVia = config('cuztomisable.login.multi_factor_authentication.send_via');
            if ($data['type'] == 'phone' && $sendVia['phone']) {
                $code->sent_at = 'phone';
                // TODO :: Sends the text message
            } else {
                $code->sent_via = 'email';
                $this->email(new MFAMail($code), $code->user->email);
            }
            $code->save();
            return $this->success([
                'message' => __('cuztomisable/authentication.mfa.sent', [
                    'sent' => $resending ? 'resent' : 'sent',
                    'location' => $data['type'] == 'phone' && $sendVia['phone'] ?
                        $code->user->mobilePhone->obscuredNumber : $code->user->obscuredEmail,
                ]),
            ]);
        } catch (Exception $error) {
            return $this->error($error);
        }
    }

    public function verify($token)
    {
        try {
            $code = Users\Code::where('token', '=', $token)
                ->whereHas('user')
                ->first();
            // Makes sure the code exists and hasn't been used
            if (!isset($code->id) || !is_null($code->used_at)) {
                throw new Exception(__('cuztomisable/authentication.mfa.errors.not_found'), 404);
            }
            // Makes sure the code hasn't expired
            if (time() > strtotime($code->expires_at)) {
                throw new Exception(__('cuztomisable/authentication.mfa.errors.expired'), 401);
            }
            $sendVia = config('cuztomisable.login.multi_factor_authentication.send_via');
            return $this->success([
                'message' => __(''),
                'verified' => true,
                'email' => $sendVia['email'] || !$sendVia['phone'] ? $code->user->obscuredEmail : null,
                'phone' => $sendVia['phone'] ? ($code->user->mobilePhone->obscuredNumber ?? null) : null,
            ]);
        } catch (Exception $error) {
            return $this->error($error);
        }
    }

    public function data(TableRequest $request)
    {
        try {

        } catch (Exception $error) {
            return $this->error($error);
        }
    }

    public function save(MFARequest $request, $token)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $code = Users\Code::where('token', '=', $token)
                ->where('code', '=', $data['code'])
                ->whereNull('used_at')
                ->where('expires_at', '>=', date('Y-m-d H:i:s'))
                ->whereHas('user')
                ->first();
            if (!isset($code->id)) {
                throw new Exception(__('cuztomisable/authentication.mfa.errors.not_found'), 404);
            }
            if ($data['remember'] == '1') {
                $ipAddress = $code->ipAddress;
                if (!isset($ipAddress->id)) {
                    throw new Exception('', 404);
                }
                $ipAddress->remember = true;
                $ipAddress->remember_until = date('Y-m-d H:i:s', strtotime('+5 minutes'));
                $ipAddress->save();
            }
            // Determines the length of time the token will remain active
            $rememberFor = (isset($data['remember']) && $data['remember'] == '1') ||
                is_null(config('cuztomisable.login.session_length', null)) ?
                    now()->addDays(60) : now()->addSeconds(config('cuztomisable.login.session_length', null));
            // Removes all older tokens for this specific user and IP Address
            $code->user->tokens()
                ->where('name', '=', $tokenName = $code->user->id.'-'.$code->ipAddress->id.'-token')
                ->delete();
            // Creates the new token for the user to log in
            $token = $code->user->createToken(
                    $tokenName, 
                    [$code->user->admin ? 'admin' : 'user'],
                    $rememberFor
                )->plainTextToken;
            $code->used_at = date('Y-m-d H:i:S');
            $code->save();
            DB::commit();
            return $this->success([
                'message' => __('cuztomisable/authentication.login.logged_in'),
                'token' => $token,
                'multi_factor_authentication' => false,
                'remember' => isset($data['remember']) && $data['remember'] == '1',
                'user' => [
                    'name' => $code->user->name,
                    'email' => $code->user->email,
                    'phone' => $code->user->mobilePhone->full_phone_number ?? null,
                    'image' => $code->user->profile->output() ?? null,
                ],
            ]);
        } catch (Exception $error) {
            DB::rollback();
            return $this->error($error);
        }
    }

}
