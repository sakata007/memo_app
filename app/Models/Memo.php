<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    // use HasFactory;
    // $user_id = \Auth::id(); つまりログインユーザーのid
    public function myMemo($user_id) {
        $tag = \Request::query('tag');
        // タグがなければ、その人が持っているメモを全て取得
        if(empty($tag)) {
            return $this::select('memos.*')->where('user_id', $user_id)->where('status', 1)
            ->get();
        } else {
            // もしタグの指定があればタグで絞る　→ where(タグがクエリパラメーターで取得したものに一致)
            // ↓メモ一覧を取得
            $memos = $this::select('memos.*')
            // leftJoin はテーブル同士を連結する関数
            // tagsテーブルの’id’とmemosテーブルの’tag_id’が一致する「tagsのカラム」をmemosテーブルのカラムに結合
            ->leftJoin('tags', 'tags.id', '=', 'memos.tag_id')
            // 結合したカラムのタグの部分のnameが$tagと一致
            // （そもそも$tagは{{ tag['name'] }}で定義されているためこうなる）
            ->where('tags.name', $tag)
            // したの２つは一緒のはず
            ->where('tags.user_id', $user_id)
            ->where('memos.user_id', $user_id)
            ->where('status', 1)
            ->get();
            return $memos;
        }
    }
}
