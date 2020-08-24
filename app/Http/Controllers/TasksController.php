<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * getでtasks/にアクセスされた場合の「一覧表示処理」
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 初期化
        $data = [];
        
        // 認証済のとき
        if (\Auth::check()){
            
            // 認証済ユーザーを取得
            $user = \Auth::user();
            
            // ユーザーのタスクの一覧を作成日時の降順で取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        // タスク一覧ビューで表示
        return view('tasks.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * getでmessages/createにアクセスされた場合の「新規登録画面表示処理」
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        // タスク作成ビューを表示する
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'content' => 'required | max:255',
            'status' => 'required | max:10',
        ]);
        
        // 認証済ユーザーの投稿として作成
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済ユーザーがその投稿の所有者である場合は投稿を表示
        if (\Auth::id() === $task->user_id){
            // タスク詳細ビューで表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        }
        // 認証済ユーザーが投稿の所有者でない場合はトップページへリダイレクト
        else {
            return redirect('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済ユーザーが投稿の所有者である場合は投稿を編集
        if (\Auth::id() === $task->user_id){
            // タスク詳細ビューで表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
        // 認証済ユーザーが投稿の所有者でない場合はトップページへリダイレクト
        else {
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'content' => 'required | max:255',
            'status' => 'required | max:10',
        ]);
        
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済ユーザーがその投稿の所有者である場合は投稿を更新
        if (\Auth::id() === $task->user_id){
            // タスクを更新
            $task -> content = $request -> content;
            $task -> status = $request -> status;
            $task -> save();
        }
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済ユーザーがその投稿の所有者である場合は投稿を削除
        if (\Auth::id() === $task->user_id){
            $task -> delete();
        }
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
