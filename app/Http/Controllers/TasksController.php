<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (\Auth::check()) {
            
            // 認証済みユーザの取得
            $user = \Auth::user();
                
            $tasks = $user->tasklists()->orderBy('created_at', 'desc')->paginate(10);
            
        }
        
        return view('tasks.index', [
                'tasks' => $tasks,
            ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create', [
                'task' => $task,
        ]);
        
        return redirect('/dashboard');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        
        $request->validate([
           
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        $request->user()->tasklists()->create([
            
            
            'status' => $request->status,
            'content' => $request->content,
            
        ]);
        
        return redirect('/dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = \App\Models\Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            
            return view('tasks.show', [
                'task' => $task,
            ]);
            
        }
        else {
            return redirect('/dashboard');
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
        $task = \App\Models\Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
        else {
            return redirect('/dashboard');
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
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        $task = \App\Models\Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id)
        {
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
        }
        
        return redirect('/dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = \App\Models\Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id)
        {
        $task->delete();
        }
    
        return redirect('/dashboard');
    }
}
