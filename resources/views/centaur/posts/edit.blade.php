@extends('centaur.layout')

@section('title')
Edit {{ $post->title }} post
@endsection

@section('content')
<h1>Edit {{ $post->title }} post</h1>

<form method="POST" action="{{ route('posts.update', $post) }}">

	{{ csrf_field() }}
	{{ method_field('PUT') }}

	<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
    <label for="title" class="control-label">Title</label>
    <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}">
		{!! ($errors->has('title')) ? '<p class="text-danger">'.$errors->first('title').'</p>' : '' !!}
  </div>
	<div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
    <label for="content" class="control-label">Content</label>
    <textarea class="form-control" id="content" name="content" rows="5">{{ $post->content }}</textarea>
		{!! ($errors->has('content')) ? '<p class="text-danger">'.$errors->first('content').'</p>' : '' !!}
  </div>
	<div class="form-group">
    <button type="submit" class="btn btn-primary">Update</button>
  </div>
</form>

@endsection