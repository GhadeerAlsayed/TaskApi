<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

use Laravel\Sanctum\PersonalAccessToken;

class DeleteCondition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-condition';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expirationDate = Carbon::now()->subDays(3);

        $deletedTokens = PersonalAccessToken::where('created_at', '<=', $expirationDate)->delete();
    }
}
