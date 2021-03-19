<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Bot;

class AuthController extends Controller
{
    /**
     * Twitch user auth
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function auth()
    {
        return Socialite::driver('twitch')->redirect();
    }

    /**
     * Twitch user auth callback
     *
     * @param Request $request
     * @return false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function callback(Request $request)
    {
        if ($request) {
            $twitchUser = Socialite::driver('twitch')->user();

            $token = $request->session()->get('user_token');

            if ($token) {
                $user = User::where('token', $token)->first();

                if ($user) {
                    Bot::updateOrCreate(
                        ['id' => $twitchUser->id],
                        [
                            'name'          => $twitchUser->nickname,
                            'token'         => $twitchUser->token,
                            'refresh_token' => $twitchUser->refreshToken,
                            'refreshed_at'  => now(),
                            'user_id'       => $user->id,
                        ]
                    );

                    return Response::HTTP_CREATED;
                }

                abort(Response::HTTP_FORBIDDEN);
            }

            if ($twitchUser) {
                $user = User::find($twitchUser->id);
                if ($user) {
                    $user->timestamps = false;
                    $user->update([
                        'name'   => $twitchUser->nickname,
                        'email'  => $twitchUser->email,
                        'avatar' => $twitchUser->avatar,
                    ]);

                    Auth::login($user, true);

                    return redirect('/');
                }
            }

            return redirect('/login')->withErrors([__('Your account was not found in our database')]);
        }

        abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Twitch bot auth
     *
     * @param string $token
     * @return mixed
     */
    public function bot_auth(string $token)
    {
        $user = User::where('token', $token)->first();

        if ($user) {
            session(['user_token' => $token]);
            $scopes = [
                'chat:edit',
                'chat:read',
            ];

            return Socialite::driver('twitch')->scopes($scopes)->redirect();
        }

        abort(Response::HTTP_FORBIDDEN);
        return false;
    }
}
