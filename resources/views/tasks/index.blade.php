@extends('layouts.app')

@section('content')

    @if (Auth::check())
    
        <h1>Welcome to the Tasklist</h1>
        
        {{-- ユーザ登録ページへのリンク --}}
        {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
        {{-- ログインページへのリンク --}}
        {!! link_to_route('login', 'Login', [], ['class' => 'btn btn-lg btn-primary']) !!}
    
    @else
    
    
        <h1>タスク一覧</h1>
        
        @if (count($tasks) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>タスク</th>
                        <th>状態</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                    <tr>
                        <td>{!! link_to_route('tasks.show', $task -> id, ['task' => $task -> id]) !!}</td>
                        <td>{{$task -> content}}</td>
                        <td>{{$task -> status}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        
        {{-- メッセージ作成ページへのリンク --}}
        {!! link_to_route('tasks.create', '新規タスクの登録', [], ['class' => 'btn btn-primary']) !!}
    
    @endif

@endsection
