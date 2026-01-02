<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireDatCho extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-dat-cho';

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
        DB::table('datcho')
            ->where('xacNhan', 0)
            ->whereNotNull('ngayhethan')
            ->where('ngayhethan', '<', now())
            ->update([
                'xacNhan' => -1
            ]);
    }
}
