@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @foreach ($threads->where('parent', '=', 0) as $thread)
                <div class="panel panel-default">
                    <div class="panel-heading"><b><a href="/thread/{{$thread->id}}">{{$thread->title}}</a></b></div>
                    <div class="panel-body">
                        <p>{{$thread->content}}</p>
                        <p>Author: {{$user->find($thread->author)->name}}</p> 
                        <p>Replies {{count($threads->where('parent', '=', $thread->id))}}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
