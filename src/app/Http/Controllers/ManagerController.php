<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\csvFileRequest;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ManagerController extends Controller
{
    public function manager()
    {
        $user = Auth::user();
        return view('manager', compact('user'));
    }

    public function admin(RegisterRequest $request)
    {
        if ($request->has('owner')) {
            $data = $request->only(['name', 'email', 'password']);
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'authority' => 1,
                'password' => Hash::make($data['password']),
            ]);
            return redirect()->back()->with('success', '店舗代表者の登録が完了しました');
        }
    }

    public function mail()
    {
        $user = Auth::user();
        return view('emails.mail', compact('user'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $subject = $request->input('subject');
        $content = $request->input('content');

        $users = User::where('authority', 2)->get(); // authority が 2 のユーザーを取得

        foreach ($users as $user) {
            Mail::to($user->email)->send(new NotificationEmail($subject, $content));
        }

        return redirect()->back()->with('success', 'メールの送信が完了しました');
    }

    // Pro入会テストにて追加
    public function csv(csvFileRequest $request)
    {
        $file = $request->file('csvFile');
        $path = $file->getRealPath();
        $fp = fopen($path, 'r');

        // ヘッダー行をスキップ
        fgetcsv($fp);

        $validationErrors = []; // エラーを収集する配列
        while (($csvData = fgetcsv($fp)) !== false) {
            $validator = Validator::make([
                'name' => $csvData[0],
                'location' => $csvData[1],
                'category' => $csvData[2],
                'detail' => $csvData[3],
                'photo' => $csvData[4],
            ], [
                'name' => 'required|max:50',
                'location' => 'required|in:東京都,大阪府,福岡県',
                'category' => 'required|in:寿司,焼肉,居酒屋,イタリアン,ラーメン',
                'detail' => 'required|max:400',
                'photo' => 'required|url',
            ], [
                'name.required' => '店舗名を入力してください。',
                'name.max' => '店舗名は50文字以内で入力してください。',
                'location.required' => '所在地を入力してください。',
                'location.in' => '所在地は「東京都」「大阪府」「福岡県」のいずれかを指定してください。',
                'category.required' => 'カテゴリを入力してください。',
                'category.in' => 'カテゴリは「寿司」「焼肉」「居酒屋」「イタリアン」「ラーメン」のいずれかを指定してください。',
                'detail.required' => '店舗詳細を入力してください。',
                'detail.max' => '店舗詳細は400文字以内で入力してください。',
                'photo.required' => '画像URLを入力してください。',
                'photo.url' => '有効なURLを入力してください。',
            ]);

            if ($validator->fails()) {
                Log::warning("Validation failed for row: ", [
                    'csvData' => $csvData,
                    'errors' => $validator->errors()->all(),
                ]);
                $validationErrors[] = [
                    'row' => $csvData,
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }
            $this->InsertCsvData($csvData);
        }

        fclose($fp);

        if (!empty($validationErrors)) {
            return redirect()->back()->with('validationErrors', $validationErrors);
        }

        return redirect()->back()->with('message', 'CSVのインポートが完了しました');
    }

    public function InsertCsvData($csvData)
    {
        $user = Auth::user();

        $locations = [
            '東京都' => 1,
            '大阪府' => 2,
            '福岡県' => 3,
        ];

        $categories = [
            '寿司' => 1,
            '焼肉' => 2,
            '居酒屋' => 3,
            'イタリアン' => 4,
            'ラーメン' => 5,
        ];

        // location_idを取得
        $locationId = $locations[$csvData[1]] ?? null;

        // category_idを取得
        $categoryId = $categories[$csvData[2]] ?? null;

        Shop::create([
            'name' => $csvData[0],
            'location_id' => $locationId,
            'category_id' => $categoryId,
            'user_id' => $user->id,
            'detail' => $csvData[3],
            'photo' => $csvData[4],
        ]);
    }
}
