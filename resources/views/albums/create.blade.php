@extends('layouts.app')

@section('content')
    <h1>Create Album</h1>
    {!! Form::open(['action' => 'PostsController@store', 'method' => 'POST' , 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
        {{Form::label('title' , 'Title')}}
        {{Form::text('title','',['class' => 'form-control' , 'placeholder' => 'Title'])}}
    </div>
    <div class="form-group">
        {{Form::label('date' , 'Date')}}
        {{ Form::text('date', '', ['id' => 'datepicker', 'class' => 'form-control' ,'placeholder' => 'Date : yyyy-mm-dd']) }}
    </div>
    <div class="form-group">
        {{Form::label('body' , 'Body')}}
        {{Form::textarea('body','',['id' => 'article-ckeditor' ,'class' => 'form-control' , 'placeholder' => 'Body Text'])}}
    </div>
    <div class="form-group">
        {{Form::file('cover_image')}}
    </div>
    {{ Form::submit('Submit' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection