<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\LogMail;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $threads = Thread::orderBy('id', 'DESC')->get();
        $user = new User;
        return view('threads', compact(['threads', 'user']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'title' => [
                'required',
                'unique:threads,title',
                'min:3',
                'regex:/^[a-zA-Z]+$/u',
            ],
            'content' => 'max:255'
        ]);
        
        $author = Auth::user()->id;
        $request['author'] = $author;
        $request['parent'] = 0;
        Thread::create($request->all());
        
        //remove older threads
        $all = Thread::where('author', $author)->where('parent', 0)->orderBy('id', 'desc')->get()->toArray();
        $chunks = array_chunk($all, 5);
        if (isset($chunks[1])) {
            Thread::destroy(array_column($chunks[1], 'id'));
        }
        
        return redirect()->back();
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function comment(Request $request)
    {
        $request->validate([
            'content' => 'required|max:255'
        ]);
        
        $request['title'] = '';
        $author = Auth::user()->id;
        $request['author'] = $author;
        //Thread::create($request->all());
        //find thread author email
        $parentAuthor = User::find(Thread::find($request->parent)->author);
        
        Mail::to($parentAuthor->email)->send(new LogMail(User::find($author), $parentAuthor));
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Thread $thread)
    {
        $threads = Thread::get();
        $user = new User;
        return view('thread', compact(['threads', 'user', 'thread']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        $name = $thread->title;
        $thread->delete();
        Session::flash('message', 'Thread ' . $name . ' deleted');
        return redirect()->back();
    }
}
