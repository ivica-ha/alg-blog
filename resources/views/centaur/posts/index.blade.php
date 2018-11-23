@extends('centaur.layout')

@section('title')
Posts
@endsection

@section('content')
<div class="clearfix">
	<h1 class="pull-left">Posts {{ Request::get('status') == 'trash' ? ' - Trash' : ' - Publish'}}</h1>
	<a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg pull-right">Create new Post</a>
</div>
<div>
	<a href="{{ route('posts.index') }}" class="btn btn-success btn-xs">Published <span class="badge">{{ $publish_posts }}</span></a>
	<a href="{{ route('posts.index', 'status=trash') }}" class="btn btn-danger btn-xs">Trashed <span class="badge">{{ $trash_posts }}</span></a>
</div>
<hr>
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Title</th>
			<th>Author</th>
			<th width="210" style="text-align:right;">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($posts as $key => $post)
			<tr>
				<td>{{ $key + 1 }}</td>
				<td>{{ $post->title }}</td>
				<td>{{ $post->user->email }}</td>
				<td align="right">
					@if(Request::get('status') == 'trash')
						<a href="{{ route('posts.restore', $post->id) }}" class="btn btn-primary btn-sm">Restore</a>
						<a href="{{ route('posts.destroy', $post->id) }}" class="btn btn-danger btn-sm action_confirm" data-method="delete" data-token="{{ csrf_token() }}" data-status="{{ Request::get('status') }}">Permenantly delete</a>
					@else
						<a href="{{ route('posts.edit', $post) }}" class="btn btn-primary btn-sm">Edit</a>
						<a href="{{ route('posts.destroy', $post->id) }}" class="btn btn-danger btn-sm" data-method="delete" data-token="{{ csrf_token() }}">Delete</a>
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
 {!! $posts->links() !!}
@endsection
