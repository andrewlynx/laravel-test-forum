@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Hello, {{ Auth::user()->name }}</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (Session::has('message'))
                        <div class="alert alert-info">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    Welcome to admin area
                    @if (count($threads) > 0)
                        <h4>There are {{count($threads->where('parent', '=', 0))}} active threads:</h4>
                        @foreach ($threads->where('parent', '=', 0) as $thread)
                            <p>
                                {{ Form::open([
                                    'url' => route('thread.destroy', $thread->id),
                                    'method' => 'delete'
                                ]) }}
                                    {{ Form::submit('DELETE', ['class' => 'btn btn-sm btn-danger pull-left']) }}
                                {{ Form::close() }} 
                                <b>{{$thread->title}}</b>
                                {{substr($thread->content, 0, 75)}} <b>by</b> {{$thread->user->name}}
                                (Replies {{count($threads->where('parent', '=', $thread->id))}})
                            </p>
                        @endforeach
                    @else
                        <h3>There are no active threads now.</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
