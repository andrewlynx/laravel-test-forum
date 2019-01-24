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
    private $contentValidation = [
        'max:255',
        'regex:/(\.)$/',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Thread::query();
        
        $query->when(null !== request('author'), function($q) {
            return $q->whereIn('author', request('author', 0));
        });
        
        $query->when(null !== request('byname'), function($q) {
            return $q->orderBy('title', 'ASC');
        });
        $query->when(null == request('byname'), function($q) {
            return $q->orderBy('id', 'DESC');
        });
        
        $threads = $query->get();
        
        $authors = Thread::select(['author'])->distinct()->where('parent', '=', 0)->groupBy('author')->get();
        return view('threads', compact(['threads', 'authors']));
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
            'content' => $this->contentValidation,
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
            'content' => $this->contentValidation,
        ]);

        $author = Auth::user()->id;
        $request->merge([
            'author' => $author,
            'title' => '',
        ]);
        Thread::create($request->all());
        //find thread author email
        $parentAuthor = User::find(Thread::find($request->parent)->author);
        
        Mail::to($parentAuthor->email)->send(new LogMail(Auth::user(), $parentAuthor));
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
        return view('thread', compact(['threads', 'thread']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        return view('edit', compact(['thread']));
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
        $request->validate([
            'title' => [
                'required',
                'unique:threads,title,' . $thread->id,
                'min:3',
                'regex:/^[a-zA-Z]+$/u',
            ],
            'content' => $this->contentValidation,
        ]);
        
        $thread->fill($request->all())->save();
        Session::flash('message', 'Thread ' . $thread->title . ' updated');
        return redirect('profile');
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
