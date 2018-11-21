<?php

namespace App\Http\Controllers;

use Sentinel;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePost;

class PostController extends Controller
{
	
		public function __construct(){
			$this->middleware('sentinel.auth');
		}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
			if(Sentinel::inRole('administrator')){
				$posts = Post::orderBy('created_at', 'DESC')->paginate(10);	
			} else {
				$id = Sentinel::getUser()->id;
				$posts = Post::where('user_id', $id)->orderBy('created_at', 'DESC')->paginate(10);
			}
			
      return view('centaur.posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('centaur.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request  $request)
    {
				$request->validate([
					'title' => 'required|max:191',
					'content' => 'required',
				]);
			
        $post = new Post;
				
				$post->user_id = Sentinel::getUser()->id;
				$post->title = request('title');
				$post->content = request('content');
				$post->save();
				
				session()->flash('success', 'Uspješno ste dodali novi post!');
				
				return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
      return view('centaur.posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePost $request, Post $post)
    {
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {		
				try{
					$post->delete();
				} catch(Exception $e){
					session()->flash('danger', $e->getMessage());
				}
        
				session()->flash('success', 'Uspješno ste izbrisali <b>' . $post->title .'</b> post!');
				
				return redirect()->back();
    }
}



