<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use App\Like;
use App\User;
use App\Dislike;
use App\Comment;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth',['except' => ['index','show']]); // voor als ge exceptions wilt maken
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::orderBy('created_at','desc')->get();
        //$posts = Post::all();
        //$posts = Post::where('title', '$Post Two')->get();
        //$posts = Post::orderBy('title','desc')->take(1)->get();

        $posts = Post::where('date' , '>=' , date("Y/m/d"))->orderBy('date','asc')->paginate(10);
       // $posts = Post::orderBy('date','desc')->paginate(10);
        return view('posts.index')->with('posts',$posts);
    }

    public function index_old()
    {
        $posts = Post::where('date' , '<' , date("Y/m/d"))->orderBy('date','asc')->paginate(10);
        return view('posts.index_old')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'date' => 'required|date|after:yesterday',
            'cover_image' => 'image|nullable|max:39999'
        ]);

        // Handle File upload

        if($request->hasFile('cover_image')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //  Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        //create post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->date = $request->input('date');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        return view('posts.show')->with('post',$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        //check for correct user
        if(auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error','Unauthorized Page');
        }

        return view('posts.edit')->with('post',$post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        // Handle File upload

        if($request->hasFile('cover_image')) {
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //  Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }

        //update post
        $post = Post::find($id);
        // delete old image
        if($post->cover_image != 'noimage.jpg') {
            // Delete image
            Storage::delete('public/cover_images/'.$post->cover_image);
        }

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        //check for correct user
        if(auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error','Unauthorized Page');
        }

        if($post->cover_image != 'noimage.jpg') {
            // Delete image
            Storage::delete('public/cover_images/'.$post->cover_image);
        }
        
        $post->likes()->forceDelete();
        $post->dislikes()->forceDelete();
        $post->comments()->forceDelete();
        $post->delete();
        return redirect('/posts')->with('success','Post Removed');
    }

    public function like($id) {
        $like = new Like();
        $like->user_id = auth()->user()->id;
        $like->post_id = $id;

        //zorgen dat niemand kan liken en disliken gelijkertijd
        $dislike = Dislike::where('post_id',$id)->where('user_id',auth()->user()->id)->get();
        if ($dislike->count() > 0)
            Dislike::destroy($dislike[0]->id);

        try {
            $like->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/posts')->with('error',"Ge gaat al jong pipo");
        }
        return redirect()->action('PostsController@show',['id' => $id])->with('success','Post Liked!');
    }

    public function dislike($id)
    {
        $dislike = new Dislike();
        $dislike->user_id = auth()->user()->id;
        $dislike->post_id = $id;

        //zorgen dat niemand kan liken en disliken gelijkertijd
        $like = Like::where('post_id',$id)->where('user_id',auth()->user()->id)->get();
        if ($like->count() > 0)
            Like::destroy($like[0]->id);

        try {
            $dislike->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/posts')->with('error',"You've already disliked this post");
        }
        return redirect()->action('PostsController@show',['id' => $id])->with('success',"Post Disliked :'(");
    }

    public function comment(Request $request, $id)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);

        $comment = new Comment();

        $comment->user_id = auth()->user()->id;
        $comment->post_id = $id;
        $comment->content = $request->input('comment');

        $comment->save();

        return redirect()->action('PostsController@show',['id' => $id])->with('success', 'Commentaar toegevoegd');
    }

}
