<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RecentPosts extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'recentposts';
  }



}
