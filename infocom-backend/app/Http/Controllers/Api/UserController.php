<?php

namespace App\Http\Controllers\Api;

use App\Handlers\SMSHandler;
use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\ForgetPasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function getUserType(User $user)
    {
        $roles = $user->getRoleNames();
        if ($roles->count()) {
            $user->load($roles[0]);
        }
        return $user;
    }

    public function login(LoginRequest $request)
    {
        $info = $request->validated();
        $user = User::where('code', $info['code'])->firstOrFail();
        if ($user && Hash::check($info['password'], $user->password)) {
            $userTokenHandler = new UserTokenHandler();
            $user = $this->getUserType($userTokenHandler->regenerateUserToken($user));

            return response()->json($user);
        }

        return response()->json('Invalid Credentials', 401);
    }

    private function generatePassword()
    {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $random_string = substr(str_shuffle(str_repeat($pool, 20)), 0, 8);
        return $random_string;
    }


    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $info = $request->validated();
        $user = User::where('code', $info['code'])->firstOrFail();
        $info['name'] = $user->name;
        $info['phone'] = $user->phone;
        $info['code'] = $user->code;
        $info['email'] = $user->email;
        $info['password'] = $this->generatePassword();

        \DB::beginTransaction();
        try {
            $userTokenHandler = new UserTokenHandler();
            $userTokenHandler->updateUser($user->id, $info);

            $message = "Your Infocom CMS password is " . $info['password'];
            SMSHandler::sendSMS($user->phone, $message);
        } catch
        (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        DB::commit();

        return response()->noContent();
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $userTokenHandler = new UserTokenHandler();
        $userTokenHandler->changePassword($request->user('api')->id, $request->validated());

        return response()->noContent();
    }


//    public function fetchUserById($user_id)
//    {
//        // TODO: Implement fetchUserById() method.
//        $user = User::where('id', $user_id)->with('employee')->firstOrFail();
//        return $user;
//    }
}
