<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;
		
		/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content', 'user_id'];
		
		/**
     * Update post.
     *
		 * @param array $data
     * @return void
     */
		public function updatePost($data = array())
		{
			$this->update($data);
		}

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
		
		/**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
