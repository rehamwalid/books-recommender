<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Book extends Model
{
    use HasFactory;
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'book_users')
            ->withPivot(['start_page','end_page']);
    }


    public function numOfPages()
    {
        $readPages = $this->users;

        $uniquePages=[];
        foreach ($readPages as $readPage) {
            //get all pages read
            $uniquePages = array_merge($uniquePages, range($readPage->pivot->start_page, $readPage->pivot->end_page));
        }
            //get number of unique pages read
            $this->num_read_pages = count(array_unique($uniquePages));
            $this->save();
        }
}
