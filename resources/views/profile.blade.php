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
                    @if (count($threads) > 0)
                        <h4>You have {{count($threads)}} active threads:</h4>
                        @foreach ($threads as $thread)
                            <p>
                                <a href="thread/{{ $thread->id }}" class="btn btn-sm btn-primary pull-left">VIEW</a>
                                
                                <a href="thread/{{ $thread->id }}/edit" class="btn btn-sm btn-success pull-left">EDIT</a>

                                {{ Form::open([
                                    'url' => route('thread.destroy', $thread->id),
                                    'method' => 'delete'
                                ]) }}
                                    {{ Form::submit('DELETE', ['class' => 'btn btn-sm btn-danger pull-left']) }}
                                {{ Form::close() }} 
                                <b>{{$thread->title}}</b>
                                {{substr($thread->content, 0, 75)}}
                            </p>
                        @endforeach
                        @if (count($threads) > 4)
                            You can have only 5 active threads. If you create new thread, the oldest will be deleted.
                        @endif
                    @else
                        <h3>You have no active threads now.</h3>
                    @endif
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Create new thread.</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{ Form::open(['url' => 'thread']) }}
                    <div class="row">
                        <div class='form-group col-md-12'>
                            <div class="row">
                                <div class='form-group col-md-3'>
                                    {{ Form::label('title', 'Title (minimum 3 characters, letters only)') }}
                                </div>
                                <div class='form-group col-md-9'>
                                    {{ Form::text('title', Input::old('title'), ["class" => "form-control"]) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class='form-group col-md-3'>
                                    {{ Form::label('content', 'Content (maximum 255 characters, must end with dot)') }}
                                </div>
                                <div class='form-group col-md-9'>
                                    {{ Form::textarea('content', Input::old('content'), ["class" => "form-control"]) }}
                                </div>
                            </div>
                            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
