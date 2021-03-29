<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Language Lines
    | 認証言語
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    | 次の言語行は、ユーザーに表示する必要のあるさまざまなメッセージの認証時に使用されます。
    | これらの言語の行は、アプリケーションの要件に応じて自由に変更できます。
    */

    'account_already_exists' => 'アカウントがすでに存在します',
    'account_country_invalid_address' => 'アカウントの国籍とビジネスを行う国籍が異なります',
    'account_invalid' => 'アカウントが不正です',
    'account_number_invalid' => '口座番号が不正です',
    'alipay_upgrade_required' => 'Alipayのアップデートが必要です',
    'amount_too_large' => '金額が多すぎます',
    'amount_too_small' => '金額が少なすぎます',
    'api_key_expired' => 'APIキーが失効しています',
    'balance_insufficient' => '残高不足です',
    'bank_account_exists' => '銀行口座がすでに存在します',
    'bank_account_unusable' => 'この銀行口座に振り込むことができません 他の口座を入力してください',
    'bank_account_unverified' => 'この口座はまだ承認されていません',
    'bitcoin_upgrade_required' => 'ビットコインのアップデートが必要です',
    'card_declined' => 'このカードはご利用できません',
    'charge_already_captured' => 'この決済はすでにキャプチャ済みです',
    'charge_already_refunded' => 'この決済はすでに返金済みです',
    'charge_disputed' => 'この決済はチャージバック中です',
    'charge_exceeds_source_limit' => 'この決済は上限を超過しています',
    'charge_expired_for_capture' => 'この決済はキャプチャ期間を過ぎています',
    'country_unsupported' => '指定された国ではサポートされていません',
    'coupon_expired' => 'クーポンが失効しています',
    'customer_max_subscriptions' => 'サブスクリプションの上限を超過しています',
    'email_invalid' => 'Emailが不正です',
    'expired_card' => 'カードの有効期限が失効しています',
    'idempotency_key_in_use' => '現在、処理が混み合っています しばらくしてから再度処理を行ってください',
    'incorrect_address' => 'カードの住所情報が誤っています 再度入力するか、他のカードをご利用ください',
    'incorrect_cvc' => 'カード裏面のセキュリティーコードが誤っています 再度入力するか、他のカードをご利用ください',
    'incorrect_number' => 'カード番号が誤っています  再度入力するか、他のカードをご利用ください',
    'incorrect_zip' => 'カードの郵便番号が誤っています  再度入力するか、他のカードをご利用ください',
    'instant_payouts_unsupported' => 'このデビットカードは即入金に対応していません  他のカードをご利用いただくか、銀行口座を入力してください',
    'invalid_card_type' => '対応していないカードタイプです 他のカードをご利用いただくか、銀行口座を入力してください',
    'invalid_charge_amount' => '不正な金額です',
    'invalid_cvc' => 'カード裏面のセキュリティーコードが誤っています',
    'invalid_expiry_month' => 'カードの有効期限(月)が誤っています',
    'invalid_expiry_year' => 'カードの有効期限(年)が誤っています',
    'invalid_number' => 'カード番号が不正です 再度入力するか、他のカードをご利用ください',
    'invalid_source_usage' => '不正な支払いソースです',
    'invoice_no_customer_line_items' => '請求書が存在しません',
    'invoice_no_subscription_line_items' => '請求書が存在しません',
    'invoice_not_editable' => 'この請求書は書き換え不可です',
    'invoice_upcoming_none' => '請求書が存在しません',
    'livemode_mismatch' => 'APIキーが不正です',
    'missing' => '支払い情報のリンクに失敗しました',
    'order_creation_failed' => '注文が失敗しました。 注文を再度確認するか、しばらくしてから再度処理を行ってください',
    'order_required_settings' => '情報に不足があるため、注文に失敗しました',
    'order_status_invalid' => '注文状態が不正なため、更新できません',
    'order_upstream_timeout' => '注文がタイムアウトしました しばらくしてから再度処理を行ってください',
    'out_of_inventory' => '在庫が無いため注文できません',
    'parameter_invalid_empty' => '情報が不足しています',
    'parameter_invalid_integer' => '不正な整数値です',
    'parameter_invalid_string_blank' => '空白文字エラーです',
    'parameter_invalid_string_empty' => '少なくとも1文字以上を入力してください',
    'parameter_missing' => '情報が不足しています',
    'parameter_unknown' => '不正なパラメータが存在します',
    'payment_method_unactivated' => '支払い方法がアクティベートされていないため、決済に失敗しました',
    'payouts_not_allowed' => 'このアカウントに入金できません 状態を確認してください',
    'platform_api_key_expired' => 'プラットフォームAPIキーが失効しています',
    'postal_code_invalid' => '郵便番号が不正です',
    'processing_error' => '処理中にエラーが発生しました 再度入力するか、他のカードをご利用ください',
    'product_inactive' => 'この商品は現在取り扱いをしていません',
    'rate_limit' => 'API上限を超過しました',
    'resource_already_exists' => 'リソースがすでに存在します',
    'resource_missing' => 'リソースが存在しません',
    'routing_number_invalid' => '口座番号、支店番号が誤っています',
    'secret_key_required' => 'シークレットキーが存在しません',
    'sepa_unsupported_account' => 'このアカウントはSEPAに対応していません',
    'shipping_calculation_failed' => '送料計算に失敗しました',
    'sku_inactive' => 'SKUに対応していません',
    'state_unsupported' => 'この州には現在対応していません',
    'tax_id_invalid' => 'TAX IDが不正です 少なくとも9桁入力する必要があります',
    'taxes_calculation_failed' => '税金計算に失敗しました',
    'testmode_charges_only' => 'テストモードの決済限定です',
    'tls_version_unsupported' => 'このTLSのバージョンに対応していません',
    'token_already_used' => 'このトークンはすでに使用済みです',
    'token_in_use' => 'このトークンは現在使用中です',
    'transfers_not_allowed' => '現在、送金が行えません',
    'upstream_order_creation_failed' => '注文に失敗しました 注文を再度確認するか、しばらくしてから再度処理を行ってください',
    'url_invalid' => 'URLが不正です'

];
