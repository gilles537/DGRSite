@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-default">Go Back</a>
    <h1>{{$post->title}}</h1>
    <div class="container">
        <img class="img-responsive" src="/storage/cover_images/{{$post->cover_image}}" width="460" height="345">
    </div>
    <br><br>
    <div>
        {!! $post->body!!}
    </div>
    <hr>
    <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
    <hr>
    @if(Auth::user()->id == $post->user_id)
        <a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</a>

        {!! Form::open(['action' => ['PostsController@destroy',$post->id], 'method' => 'POST' , 'class' => 'pull-right']) !!}
            {{Form::hidden('_method' , 'DELETE')}}
            {{Form::submit('Delete' , ['class' => 'btn btn-danger'])}}
        {!! Form::close() !!}
    @endif

    {!! Form::open(['action' => ['PostsController@like',$post->id], 'method' => 'POST']) !!}
    {{Form::hidden('_method' , 'POST')}}
    {{Form::submit('Like' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

@endsection