<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table = 'messages';
    protected $guarded = [];
    protected $perPage = 25;

//    public static function getPerPage($page)
//    {
//        $page = (int)$page;
//        $id_end = $page * 25;
//        $id_start = $id_end - 24;
//        return Messages::select([
//            'messages.*',
//            'users.name as user_name',
//            'users.avatar as user_avatar'
//        ])
//            ->where('messages.id', '<=', $id_end)
//            ->where('messages.id', '>=', $id_start)
//            ->join('users', 'messages.user_id', '=', 'users.id')
//            ->get();
//    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
