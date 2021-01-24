@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">商品の編集</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('admin.item.edit', ['id' => $item->id]) }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">商品名</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $item->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('explanation') ? ' has-error' : '' }}">
                            <label for="explanation" class="col-md-4 control-label">説明文</label>

                            <div class="col-md-6">
                                <input id="explanation" type="text" class="form-control" name="explanation" value="{{ $item->explanation }}" required autofocus>

                                @if ($errors->has('explanation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('explanation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('stock') ? ' has-error' : '' }}">
                            <label for="stock" class="col-md-4 control-label">在庫数</label>

                            <div class="col-md-6">
                                <input id="stock" type="number" class="form-control" name="stock" value="{{ $item->stock }}" required autofocus>

                                @if ($errors->has('stock'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('stock') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                 編集
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
			<a href="{{ route('admin.item', ['id' => $item->id]) }}">商品詳細へ戻る</a><br>
            <a href="{{ route('admin.items') }}">商品一覧へ</a>
        </div>
    </div>
</div>
@endsection
