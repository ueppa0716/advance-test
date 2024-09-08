<p>こんにちは、{{ $user->name }}さん</p>

<p>ご予約日の当日となりましたので、リマインドさせていただきます。</p>

<ul>
    <li>店舗: {{ $shop_name }}</li>
    <li>日付: {{ $date }}</li>
    <li>人数: {{ $people }}名</li>
</ul>

<div>{{ $qrCode }}</div>

<p>お待ちしております！</p>
