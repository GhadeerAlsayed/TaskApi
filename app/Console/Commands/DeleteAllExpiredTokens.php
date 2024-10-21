<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class DeleteAllExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-all-expired-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DeleteExpiredTokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedTokens = PersonalAccessToken::query()->delete();


    }
}
