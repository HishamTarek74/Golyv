<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{


    /**
     * Handle a login request to the application.
     *
     * @param LoginRequest $request
     * @return UserResource|JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::whereEmail($request->email)->firstOrFail();

            return $this->getAuthUserResponse($user->fresh());
        }
        return throw ValidationException::withMessages(
            ['email' => trans('auth.failed')]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully Logged Out'], 202);
    }


    /**
     * @param User $user
     * @return UserResource
     */
    private function getAuthUserResponse(User $user)
    {
        return $user->getResource()->additional([
            'token' => $user->createTokenForDevice(request()->header('user-agent')),
        ]);
    }
}
