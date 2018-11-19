@extends('Centaur::layout')
@section('title', 'Posts')

@section('content')

<div class="clearfix">
  <h1 class="pull-left">Posts</h1>
  <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg pull-right">Create a new Post</a>
</div>
<hr>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Title</th>
      <th>Author</th>
      <th width="150">Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($posts as $post)
      <tr>
        <td></td>
        <td>{{ $post->title }}</td>
        <td></td>
        <td></td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $posts->links() }}

@endsection
