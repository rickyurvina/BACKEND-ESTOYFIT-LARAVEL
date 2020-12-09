<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalendarActivity extends Model
{
    protected $table = 'calendar_activities';
    protected $fillable = [
        'id', 'branch_id', 'service_id', 'title', 'description', 'start', 'end', 'start_lesson', 'end_lesson', 'show_in_front', 'active'
    ];

    protected $columns = ['title']; // add all columns from you table

    public function scopeExclude($query, $value = [])
    {
        return $query->select(array_diff($this->columns, (array) $value));
    }


    public function get_calendar_activities($page, $limit)
    {
        $result = CalendarActivity::where('calendar_activities.active', 1)
            ->leftJoin('gym_branch', 'calendar_activities.branch_id', '=', 'gym_branch.id')
            ->select(
                DB::raw('CONCAT(gym_branch.commercial_name, ", ", calendar_activities.title) AS title'),
                'calendar_activities.title as titleLesson',
                'calendar_activities.id as id',
                'calendar_activities.branch_id as branch_id',
                'calendar_activities.description as description',
                'calendar_activities.start as start',
                'calendar_activities.end as end',
                'calendar_activities.start_lesson as start_lesson',
                'calendar_activities.end_lesson as start_lesson',
                'calendar_activities.show_in_front as show_in_front',
                'calendar_activities.active as active'
            )
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->get();
        return $result;
    }

    public function get_calendar_activities_by_branch($page, $limit, $branch_id)
    {
        $result = CalendarActivity::where('calendar_activities.active', 1)
            ->where('calendar_activities.branch_id', $branch_id)
            ->leftJoin('gym_branch', 'calendar_activities.branch_id', '=', 'gym_branch.id')
            ->select('calendar_activities.*', 'gym_branch.commercial_name as gymName')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->get();
        return $result;
    }


    public function create_calendar_activity($objectSave)
    {
        $rowCreated = CalendarActivity::create($objectSave);
        $response = CalendarActivity::where('id', $rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function update_calendar_activity($id, $objectSave)
    {
        $update = CalendarActivity::find($id)->update($objectSave);
        $response = CalendarActivity::where('id', $id)->first();
        return $response;
    }

    public function delete_calendar_activity($id)
    {
        $response = CalendarActivity::find($id)->delete();

        return $response;
    }

    public function set_show_calendar_activity($id, $show_value)
    {
        $update = CalendarActivity::find($id)->update(['show_in_front' => $show_value]);
        $response = CalendarActivity::where('id', $id)->first();
        return $response;
    }
}
