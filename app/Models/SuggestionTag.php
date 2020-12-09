<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuggestionTag extends Model
{
    protected $table = 'suggestion_tags';
    protected $fillable = [
        'id', 'id_row', 'type', 'name', 'categories',
    ];

    public function get_suggestion_tags()
    {
        $result = SuggestionTag::get();
        return $result;
    }

    public function save_sugestion_tag($objectSaveSuggestionTag)
    {

        $search = SuggestionTag::where('type', $objectSaveSuggestionTag['type'])
            ->where('id_row', $objectSaveSuggestionTag['id_row'])
            ->first();

        if ($search['id']) {
            $result = SuggestionTag::find($search['id'])->update($objectSaveSuggestionTag);
        } else {
            $result = SuggestionTag::create($objectSaveSuggestionTag);
        }

        return $result;
    }

    public function save_sugestion_tag_sector($objectSaveSuggestionTagSector)
    {

        $search = SuggestionTag::where('type', '0')
            // ->where('name', 'like', '%'.$objectSaveSuggestionTagSector['name'].'%')
            ->where('name', $objectSaveSuggestionTagSector['name'])
            ->first();

        if ($search['id']) {
            $result = SuggestionTag::find($search['id'])->update($objectSaveSuggestionTagSector);
        } else {
            $result = SuggestionTag::create($objectSaveSuggestionTagSector);
        }

        return $result;
    }



    public function get_test($text)
    {
        // $columns = implode(',',$this->searchable);
        // $searchableTerm = $this->fullTextWildcards($text);

        // $result = SuggestionTag::whereRaw('MATCH (name) AGAINST (?)' , array($text))->get();
        $result = SuggestionTag::where('name', 'like', '%' . $text . '%')->get();
        return $result;
    }
}
