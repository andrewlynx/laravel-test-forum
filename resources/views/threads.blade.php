@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p>
                <a href="/thread" class="btn btn-sm btn-primary">Sort by date</a> 
                <a href="{{url('thread') . '?' . http_build_query(['byname' => '1'])}}" class="btn btn-sm btn-primary">Sort by name</a>
            </p>
            <p>
                {{ Form::open([
                    'url' => route('thread.index'),
                    'method' => 'get'
                ]) }}
                    @foreach ($authors as $author)
                        {{ Form::checkbox('author[' . $author->user->id . ']', $author->user->id) }}
                        {{ Form::label('author[' . $author->user->id . ']', $author->user->name) }}
                    @endforeach
                    {{ Form::submit('Filter', ['class' => 'btn btn-sm btn-primary']) }}
                {{ Form::close() }}
            </p>
            @foreach ($threads->where('parent', '=', 0) as $thread)
                <div class="panel panel-default">
                    <div class="panel-heading"><b><a href="/thread/{{$thread->id}}">{{$thread->title}}</a></b></div>
                    <div class="panel-body">
                        <p>{{$thread->content}}</p>
                        <p>Author: {{$thread->user->name}}</p> 
                        <p>Replies {{count($threads->where('parent', '=', $thread->id))}}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
