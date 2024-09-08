<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '予約当日の朝にユーザー対して、予約情報のリマインドメールを送る';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');

        // 今日の予約データを取得
        $reserveLists = Reservation::whereDate('date', '=', $today)->get();

        foreach ($reserveLists as $reservation) {
            // 予約の詳細を取得
            $user = User::find($reservation->user_id);
            $shop = Shop::find($reservation->shop_id);

            if ($user && $shop) {
                $qrCode = QrCode::encoding('UTF-8')
                    ->size(200)
                    ->generate(json_encode([
                        'ご予約店舗名' => $shop->name,
                        'ご予約者様氏名' => $user->name,
                        'ご予約日時' => Carbon::parse($reservation->date)->setTimezone('Asia/Tokyo')->format('Y-m-d H:i'),
                        'ご予約人数' => $reservation->people,
                    ], JSON_UNESCAPED_UNICODE));

                // メールのデータを準備
                $mailData = [
                    'user' => $user,
                    'date' => $reservation->date,
                    'people' => $reservation->people,
                    'shop_name' => $shop->name,
                    'qrCode' => $qrCode,
                ];

                // メールを送信
                Mail::send('emails.reminder', $mailData, function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('ご予約のリマインドメール');
                });
            }
        }

        return 0;
    }
}
