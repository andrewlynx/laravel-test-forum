@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('message'))
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            @endif
            {{ Form::open(['url' => 'thread/' . $thread->id, 'method' => 'put']) }}
                <div class="row">
                    <div class='form-group col-md-12'>
                        <div class="row">
                            <div class='form-group col-md-3'>
                                {{ Form::label('title', 'Title (minimum 3 characters, letters only)') }}
                            </div>
                            <div class='form-group col-md-9'>
                                {{ Form::text('title', $thread->title, ["class" => "form-control"]) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class='form-group col-md-3'>
                                {{ Form::label('content', 'Content (maximum 255 characters)') }}
                            </div>
                            <div class='form-group col-md-9'>
                                {{ Form::textarea('content', $thread->content, ["class" => "form-control"]) }}
                            </div>
                        </div>
                        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
