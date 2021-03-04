@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">

@if (session('success'))
<div class="alert alert-success text-center">
{{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert alert-danger text-center">
{{ session('error') }}
</div>
@endif

<h3>クレジットカード情報</h3>
@if ($cards_count < 3)
<div class="form-group">
<a href="{{ route('cards.create') }}">
<button type="submit" class="btn btn-primary">
<font color="white">クレジットカードを追加する</font>
</button>
</a>
</div>
@endif


@if (!empty($cards->data[0]))
@foreach ($cards as $key => $card)
<div class="panel panel-default">
<div class="panel-heading">名義人： {{ $card->name }}</div>
<label>
<ul style="list-style:none;">
<li>ブランド {{ $card->brand }}</li>
<li>末尾 {{ $card->last4 }}</li>
<li>有効期限 {{ $card->exp_month }} / {{ $card->exp_year }}</li>
<br>
@if ($default_card_id === $card->id)
現在デフォルトに指定されています
@else
<span><a href="{{ route('cards.edit', ['card_id' => $card->id]) }}">デフォルトに変更</a> |
<a href="{{ route('cards.delete', ['card_id' => $card->id]) }}">削除</a></span>
@endif
</ul>
</label>
</div>
@endforeach
</form>
@else
<div class="panel panel-default">
<h3>　クレジットカードが登録されていません<br>
<br>『クレジットカードを追加する』から登録して下さい</h3>
</div>
@endif

<a href="{{ route('items') }}">商品一覧へ</a>
</div>
</div>
</div>
</div>

@endsection
