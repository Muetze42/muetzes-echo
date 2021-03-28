<?php

namespace App\Console\Commands;

use App\Models\Bot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use TwitchApi\TwitchApi;

class RefreshTokens extends Command
{
    protected $twitchApi;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Autorestart Bots';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \TwitchApi\Exceptions\ClientIdRequiredException
     */
    public function handle()
    {
        $time = now()->subHours(config('services.twitch.token_refresh', 2));
        $bots = Bot::where('refreshed_at', '<', $time)->get();
        Log::debug('Start Refresh Token');
        foreach ($bots as $bot) {
            Log::debug('Refresh: '.$bot->id);
            if (!$this->twitchApi) {
                $options = [
                    'client_id'     => config('services.twitch.client_id'),
                    'client_secret' => config('services.twitch.client_secret'),
                ];

                $twitchApi = new TwitchApi($options);

                $new = $twitchApi->refreshToken($bot->refresh_token);

                if ($new && isset($new['access_token']) && isset($new['refresh_token']) && $new['access_token'] && $new['refresh_token']) {
                    $bot->update([
                        'token'         => $new['access_token'],
                        'refresh_token' => $new['refresh_token'],
                        'refreshed_at'  => now(),
                    ]);
                    sleep(0.5);
                    Artisan::call('bot:restart '.$bot->id);
                }
            }
        }
    }
}
