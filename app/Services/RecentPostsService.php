<?php

namespace App\Services;

Use Illuminate\View\Factory;
use App\Models\Post;

class RecentPostsService
{
  protected $view;

public function __construct(Factory $view)
  {
    $this->view = $view;
  }

  public function generate()
  {
    $posts = Post::orderBy('created_at', 'DESC')->take(5)->get();

    $html = $this->view->make('centaur.partials.recent-posts', ['posts' => $posts])->render();

    return $html;
  }
}
