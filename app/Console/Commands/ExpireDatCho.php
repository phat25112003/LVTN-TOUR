<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\DatCho;

class ExpireDatCho extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire-dat-cho';

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
        DB::transaction(function () {

            $dsHetHan = DatCho::where('xacNhan', 0)
                ->whereNotNull('ngayhethan')
                ->where('ngayhethan', '<', now())
                ->with('chuyentour')
                ->get();

            foreach ($dsHetHan as $datCho) {

                $tongNguoi =
                    ($datCho->soNguoiLon ?? 0) +
                    ($datCho->soTreEm ?? 0) +
                    ($datCho->soEmBe ?? 0);

                if ($datCho->chuyentour) {
                    $datCho->chuyentour->soLuongDaDat = max(
                        0,
                        $datCho->chuyentour->soLuongDaDat - $tongNguoi
                    );
                    $datCho->chuyentour->save();
                }

                $datCho->xacNhan = -1;
                $datCho->save();
            }
        });
    }
}
