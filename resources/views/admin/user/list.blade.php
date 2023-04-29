@extends('layout')

{{-- メインコンテンツ --}}
@section('contents')

        @if ($errors->any())
            <div>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            </div>
        @endif

        <a href="/admin/top">管理画面Top</a><br>
        <a href="/admin/user/list">ユーザ一覧</a><br>
        <a href="/admin/logout">ログアウト</a><br>

        <h1>ユーザー一覧</h1>
        <table border="1">
        <tr>
            <th>ユーザID
            <th>ユーザ名
            <th>購入した「買うもの」の数
        @foreach ($list as $list)
        <tr>
            <td>{{ $list->id }}
            <td>{{ $list->name }}
            <td>{{ $list->task_num }}
@endforeach


@endsection