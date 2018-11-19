@extends('Centaur::layout')
@section('title', 'Create post')

@section('content')
<h1>Create a Post</h1>

<form action="/posts" method="POST">
  {{ csrf_field() }}
  <div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
    <label for="title" class="control-label">Title</label>
    <input type="text" class="form-control" name="title">
    {!! ($errors->has('title')) ? '<p class="text-danger">'.$errors->get('title')[0].'</p>' : '' !!}
  </div>
  <div class="form-group {{ ($errors->has('content')) ? 'has-error' : '' }}">
    <label class="control-label" for="content">Content</label>
    <!-- <input type="text" class="form-control" id="content" name="content"> -->
    <textarea class="form-control" rows="4"  name="content"></textarea>
    {!! ($errors->has('content')) ? '<p class="text-danger">'.$errors->get('content')[0].'</p>' : '' !!}
  </div>
  <div class="form-group">
    <button type="submit" class="btn btn-primary">Publish</button>
  </div>
</form>

@endsection
