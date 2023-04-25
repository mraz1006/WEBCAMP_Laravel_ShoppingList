@extends('layout')

{{-- タイトル --}}
@section('title')(一覧画面)@endsection

{{-- メインコンテンツ --}}
@section('contents')
<h1>「買うもの」の登録</h1>
<form action="/shopping_list/register" method="post">
        @if (session('front.shoppinglists_register_success') == true)
                買い物リストに追加しました！！<br>
        @endif
      @if ($errors->any())
            <div>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            </div>
        @endif
                @csrf
                「買うもの」名:<input name="name" value="{{ old('name') }}"><br>
                <button>「買うもの」を登録する</button>
            </form>

        <h1>買い物リスト</h1>
@endsection