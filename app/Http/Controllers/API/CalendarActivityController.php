<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CalendarActivity;
use App\Models\Service;
use Carbon\Carbon;

class CalendarActivityController extends Controller
{
    public function get_calendar_activities(Request $request)
    {

        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 10;

        $calendar_activity = new CalendarActivity;
        $calendar_activities = $calendar_activity->get_calendar_activities($page, $limit);

        $service = new Service;
        $services = $service->get_services();

        return response()->json([
            'data' => $calendar_activities,
            'services' => $services
        ]);
    }

    public function get_calendar_activities_by_branch(Request $request)
    {

        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $branch_id = $request->get('branch_id');

        $calendar_activity = new CalendarActivity;
        $calendar_activities = $calendar_activity->get_calendar_activities_by_branch($page, $limit, $branch_id);

        $service = new Service;
        $services = $service->get_services();

        return response()->json([
            'data' => $calendar_activities,
            'services' => $services
        ]);
    }

    

    public function save_calendar_activity(Request $request)
    {
        $calendar_activity = new CalendarActivity;
        $id = $request->input('id');
        $branch_id = $request->input('branch_id');
        $service_id = $request->input('service_id');
        $description = $request->input('description');
        $title = $request->input('title');
        $start = $request->input('start');
        $end = $request->input('end');
        $start_lesson = $request->input('start_lesson');
        $end_lesson = $request->input('end_lesson');

        $objectSave = [
            'service_id' => $service_id,
            'branch_id' => $branch_id,
            'title' => $title,
            'description' => $description,
            'start' => Carbon::parse($start),
            'end' => Carbon::parse($end),
            'start_lesson' => Carbon::parse($start_lesson),
            'end_lesson' => Carbon::parse($end_lesson),
        ];

        if ($id != 'null') {

            $response = $calendar_activity->update_calendar_activity($id, $objectSave);
        } else {
            $response_id = $calendar_activity->create_calendar_activity($objectSave);
        }

        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $data = $calendar_activity->get_calendar_activities_by_branch($page, $limit, $branch_id);

        return response()->json([
            "error" => "",
            "data" => $data,
            "starend" => Carbon::parse($start)
        ]);
    }

    public function delete_calendar_activity(Request $request)
    {
        $calendar_activity = new CalendarActivity;
        $id = $request->input('id');
        $response = $calendar_activity->delete_calendar_activity($id);

        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $data = $calendar_activity->get_calendar_activities($page, $limit);

        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }

    public function set_show_calendar_activity(Request $request)
    {
        $calendar_activity = new CalendarActivity;
        $id = $request->input('id');
        $show_value = $request->input('show_value');
        $response = $calendar_activity->set_show_calendar_activity($id, $show_value);

        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $data = $calendar_activity->get_calendar_activities($page, $limit);

        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }

    

    
}
