@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-default">Go Back</a>
    <h1>{{$post->title}}</h1>
    <h4>Datum: {{$post->date}}</h4>
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
        <br><br>
    @endif
    @if(strtotime($post->date) > strtotime(date("Y/m/d")))
    <a class="btn btn-success" href="{!! route('like', ['id'=>$post->id]) !!}">We zijn!</a>
    <a class="btn btn-danger pull-right" href="{!! route('dislike', ['id'=>$post->id]) !!}">Nee maat ik kan nie</a>
    <hr><br>
    @endif
    <div class="container">
        <p>Mensen die kunnen:</p>
        <ul class="list-group pull-left">
            @if(count($post->likes) > 0)
                @foreach($post->likes as $like)
                    <li class="list-group-item">   {{ $like->user->name }}</li>
                @endforeach
            @else
                <p class="text-uppercase" style="color:red" >Nog niemand gaat op de moment snap ge</p>
            @endif
        </ul>

        <ul class="list-group pull-right">
            <p>Mensen die niet kunnen:</p>
            @if(count($post->dislikes) > 0)
                @foreach($post->dislikes as $dislike)
                    <li class="list-group-item">   {{ $dislike->user->name }}</li>
                @endforeach
            @else
                <p class="text-uppercase" style="color:green" >Tot nu toe nog niemand geplooid</p>
            @endif
        </ul>
    </div>
    <p>Commentaar:</p>
    @if(count($post->comments) > 0)
        @foreach($post->comments as $comment)
            <li class="list-group-item"><b>{{$comment->user->name}}: </b>{{ $comment->content }}</li>
        @endforeach
        <br>
    @else
        <p class="text-uppercase" style="color:red" >Nog geen comments :(</p>
        <br>
    @endif

    <div class="container">
    {!! Form::open(['action' => ['PostsController@comment', $post->id], 'method' => 'POST' , 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
        {{Form::label('comment' , 'Comment')}}
        {{Form::text('comment','',['class' => 'form-control' , 'placeholder' => 'Voeg een reactie toe:'])}}
    </div>
    {{Form::hidden('_method','PUT')}}
    {{ Form::submit('Submit' , ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    </div>
    <br><br>
@endsection