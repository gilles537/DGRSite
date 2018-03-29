@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-default">Go Back</a>
    <h1>{{$data['post']->title}}</h1>
    <div class="container">
        <img class="img-responsive" src="/storage/cover_images/{{$data['post']->cover_image}}" width="460" height="345">
    </div>
    <br><br>
    <div>
        {!! $data['post']->body!!}
    </div>
    <hr>
    <small>Written on {{$data['post']->created_at}} by {{$data['post']->user->name}}</small>
    <hr>
    @if(Auth::user()->id == $data['post']->user_id)
        <a href="/posts/{{$data['post']->id}}/edit" class="btn btn-default">Edit</a>

        {!! Form::open(['action' => ['PostsController@destroy',$data['post']->id], 'method' => 'POST' , 'class' => 'pull-right']) !!}
            {{Form::hidden('_method' , 'DELETE')}}
            {{Form::submit('Delete' , ['class' => 'btn btn-danger'])}}
        {!! Form::close() !!}
        <br><br>
    @endif
    @if(strtotime($data['post']->date) > strtotime(date("Y/m/d")))
    <a class="btn btn-success" href="{!! route('like', ['id'=>$data['post']->id]) !!}">We zijn!</a>
    <a class="btn btn-danger pull-right" href="{!! route('dislike', ['id'=>$data['post']->id]) !!}">Nee maat ik kan nie</a>
    <br><br>
    @endif
    <div class="container">
        <p>Mensen die kunnen:</p>
        <ul class="list-group pull-left">
            @if(count($data['likeArray']) > 0)
                @foreach ( $data['likeArray'] as $key => $value )
                    <li class="list-group-item">   {{ $value }}</li>
                @endforeach
            @else
                <p class="text-uppercase" style="color:red" >Nog niemand gaat op de moment snap ge</p>
            @endif
        </ul>

        <ul class="list-group pull-right">
            <p>Mensen die niet kunnen:</p>
            @if(count($data['dislikeArray']) > 0)
                @foreach ( $data['dislikeArray'] as $key => $value )
                    <li class="list-group-item">   {{ $value }}</li>
                @endforeach
            @else
                <p class="text-uppercase" style="color:green" >Tot nu toe nog niemand geplooid</p>
            @endif
        </ul>
    </div>
    <p>Commentaar:</p>
    @if(count($data['commentsArray']) > 0)
        @for ($x =0 ; $x < count($data['commentsArray']) ; $x++)
            <li class="list-group-item"><b>{{$data['commentUsers'][$x]}}: </b>{{ $data['commentsArray'][$x]->content }}</li>
            <br>
        @endfor
    @else
        <p class="text-uppercase" style="color:red" >Nog geen comments :(</p>
        <br>
    @endif

    <div class="container">
    {!! Form::open(['action' => ['PostsController@comment', $data['post']->id], 'method' => 'POST' , 'enctype' => 'multipart/form-data']) !!}
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