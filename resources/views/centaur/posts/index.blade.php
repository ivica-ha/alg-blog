@extends('centaur.layout')

@section('title')
Posts
@endsection

@section('content')
<div class="clearfix">
	<h1 class="pull-left">Posts</h1>
	<a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg pull-right">Create new Post</a>
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
		@foreach($posts as $key => $post)
			<tr>
				<td>{{ $key + 1 }}</td>
				<td>{{ $post->title }}</td>
				<td>{{ $post->user->email }}</td>
				<td>
					<a href="{{ route('posts.edit', $post) }}" class="btn btn-primary btn-sm">Edit</a>
					<a href="{{ route('posts.destroy', $post) }}" class="btn btn-danger btn-sm action_confirm" data-method="delete" data-token="{{ csrf_token() }}">Delete</a>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
 {!! $posts->links() !!}
@endsection


