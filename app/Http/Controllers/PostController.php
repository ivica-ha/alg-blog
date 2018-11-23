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
    public function index(Request $request)
    {
					if(Sentinel::inRole('administrator')){
						$publish_posts = Post::all()->count();
						$trash_posts = Post::onlyTrashed()->count();
					} else {
						$id = Sentinel::getUser()->id;
						$publish_posts = Post::where('user_id', $id)->count();
						$trash_posts = Post::onlyTrashed()->where('user_id', $id)->count();
					}

			if($request->get('status') == 'trash'){
					if(Sentinel::inRole('administrator')){
						$posts = Post::onlyTrashed()->orderBy('created_at', 'DESC')->paginate(10);
					} else {
						$id = Sentinel::getUser()->id;
						$posts = Post::where('user_id', $id)->orderBy('created_at', 'DESC')->paginate(10);
					}
			}	else{
					if(Sentinel::inRole('administrator')){
						$posts = Post::orderBy('created_at', 'DESC')->paginate(10);
					} else {
						$id = Sentinel::getUser()->id;
						$posts = Post::where('user_id', $id)->orderBy('created_at', 'DESC')->paginate(10);
					}
			}


      return view('centaur.posts.index')
					->with('posts', $posts)
					->with('publish_posts', $publish_posts)
					->with('trash_posts', $trash_posts);
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
			if(Sentinel::getUser()->id == $post->user_id || Sentinel::inRole('administrator')){
      	return view('centaur.posts.edit')->with('post', $post);
    }
		abort(404);
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
			if(Sentinel::getUser()->id == $post->user_id || Sentinel::inRole('administrator')){
      	$data = array(
					'title' => trim($request->get('title')),
					'content' => $request->get('content')
				);
				try{
					$post->updatePost($data);
				} catch(Exception $e){
					session()->flash('danger', $e->getMessage());
				}
    }
		session()->flash('success', 'Uspješno ste ažurirali <b>' . $post->title .'</b> post!');

		return redirect()->route('posts.index');
		}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
			$post = Post::withTrashed()->findOrFail($id);

				try{
					if($request->get('status') == 'trash'){
						$post->forceDelete();
						$message = 'Uspješno ste izbrisali <b>' . $post->title . '</b> post!';
					}else {
						$post->delete();
						$message = 'Post <b>' . $post->title . '</b> je premješten u smeće!';
					}

				} catch(Exception $e){
					session()->flash('danger', $e->getMessage());
				}

				session()->flash('success', $message);

				return redirect()->back();
    }

		/**
     * Restore the specified resource from storage.
     *
     * @param  \int $id
     * @return \Illuminate\Http\Response
     */
		 public function restore($id)
		 {
			 $post = Post::withTrashed()->findOrFail($id);

			 try{
				 $post->restore();
				 session()->flash('success', 'Uspješno ste vratili <b>' . $post->title .'</b> post!');
				 return redirect()->route('posts.index');
			 } catch(Exception $e){
				 session()->flash('error', $e->getMessage());
				 return redirect()->back();
			 }
		 }
}
