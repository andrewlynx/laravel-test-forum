@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="/thread">< Back to thread list</a>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p><b>{{$thread->title}}</b></p>
                    Posted: {{$thread->created_at}} by <b>{{$thread->user->name}}</b>
                </div>
                <div class="panel-body">
                    <p>{{$thread->content}}</p>
                </div>
            </div>
            {{ Form::open(['url' => 'thread.comment']) }}
                    <div class="row">
                        <div class='form-group col-md-12'>
                            <div class="row">
                                <div class='form-group col-md-3'>
                                    {{ Form::label('content', 'Comment thread (maximum 255 characters)') }}
                                </div>
                                <div class='form-group col-md-9'>
                                    {{ Form::textarea('content', Input::old('content'), ["class" => "form-control"]) }}
                                    {{ Form::hidden('parent', $thread->id) }}
                                </div>
                            </div>
                            {{ Form::submit('Comment', ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
            <h4>Thread comments:</h4>
            @foreach ($threads->where('parent', '=', $thread->id) as $comment)
                <div class="panel panel-default">
                    <div class="panel-heading"><p>{{$comment->user->name}} wrote:</p> </div>
                    <div class="panel-body">
                        <p>{{$comment->content}}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
