@component('mail::message')
# ご注文完了のお知らせ

いつもSweetoをご利用いただきありがとうございます。

**Sweet**がお客様のご注文を承ったことをお知らせいたします。

@component('mail::button', ['url' => $reset_url])
注文番号の確認
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
