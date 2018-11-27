@extends('centaur.layout')

@section('title')
Create a Post
@endsection

@section('content')
<h1>Create a Post</h1>

<form method="POST" action="/posts">

	{{ csrf_field() }}

	<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
    <label for="title" class="control-label">Title</label>
    <input type="text" class="form-control" id="title" name="title">
		{!! ($errors->has('title')) ? '<p class="text-danger">'.$errors->first('title').'</p>' : '' !!}
  </div>
	<div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
    <label for="content" class="control-label">Content</label>
    <textarea class="form-control" id="content" name="content" rows="5"></textarea>
		{!! ($errors->has('content')) ? '<p class="text-danger">'.$errors->first('content').'</p>' : '' !!}
  </div>
	<div class="form-group">
    <button type="submit" class="btn btn-primary">Publish</button>
  </div>
</form>

@endsection
