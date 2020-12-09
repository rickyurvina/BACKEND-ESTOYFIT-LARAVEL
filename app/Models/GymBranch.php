<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Passe;


class GymBranch extends Model
{
    protected $table = 'gym_branch';
    protected $fillable = [
        'id',
        'active',
        'gym_id',
        'commercial_name',
        'description',
        'type',
        'resume',
        'ruc',
        'email',
        'password',
        'phone',
        'mobile',
        'city',
        'canton',
        'province',
        'parroquia',
        'sector',
        'main_street',
        'intersection',
        'numeration',
        'reference',
        'website',
        'rating_value',
        'facebook',
        'instagram',
        'youtube',
        'main_image',
        'is_image',
        'banner_image',
        'amenities',
        'services_selected',
        'parking',
        'latitude',
        'longitude',
        'week_from',
        'week_to',
        'week_from_afternoon',
        'week_to_afternoon',
        'saturday_from',
        'saturday_to',
        'saturday_from_afternoon',
        'saturday_to_afternoon',
        'freeday_from',
        'freeday_to',
        'freeday_from_afternoon',
        'freeday_to_afternoon'
    ];

    public function get_gym_branchs($id)
    {
        $result = DB::table('gym_branch')
            ->where('gym_branch.gym_id', $id)
            ->where('users.lead', '1')
            ->leftJoin('users', 'users.branch_id', '=', 'gym_branch.id')
            ->select('gym_branch.*', 'users.name as userName', 'users.email as userEmail', 'users.id as userId')
            ->get();
        return $result;
    }

    public function get_gym_all_branchs()
    {
        $result = DB::table('gym_branch')->get();
        return $result;
    }

    public function get_all_gym_branchs()
    {
        $result = DB::table('gym_branch')->select('id','commercial_name as name')->get();
        return $result;
    }

    public function get_gym_branch_data($id)
    {
        $result = GymBranch::where('id', $id)->first();
        return $result;
    }

    public function get_gym_branchs_front($city)
    {
        $result = DB::table('gym_branch')
            ->where('active', '1')
            ->where('city', $city)
            ->get();
        return $result;
    }

    public function get_gym_branchs_by_activity_front($activity, $city)
    {

        $result = DB::table('gym_branch')->where('active', '1');
        if ($city != 0) {
            $result->where('city', $city);
        }

        return $result->get();
    }

    public function get_gym_branchs_by_filter_front($filter_field, $text)
    {
        if ($filter_field == 'cities.name') {

            $result = DB::table('gym_branch')
                ->leftJoin('cities', 'cities.id', '=', 'gym_branch.city')
                ->where("cities.name", $text);
        } else {

            $result = DB::table('gym_branch')
                ->where($filter_field, 'like', '%' . $text . '%');
        }

        return $result->get();

        return  $result;
    }

    public function get_gym_branchs_by_activity_city_front($text_suggestion, $text_suggestion_two)
    {
        $service = DB::table('services')->where("name", $text_suggestion)->first();
        $service_id = strval($service->id);
        $gymBranchs = DB::table('gym_branch')
            ->join('cities', 'cities.id', '=', 'gym_branch.city')
            ->where("cities.name", $text_suggestion_two)
            ->whereRaw("JSON_CONTAINS(services_selected, ?)", [$service_id])
            ->select('gym_branch.*')
            ->get();

        foreach ($gymBranchs as $g) {
            $passes = DB::table('passes')->where('branch_id', $g->id)->select('name', 'color', 'original_price', 'discount', 'discount_value', 'train_now_price')->get();
            $services = DB::table('services')->whereIn('id', json_decode($g->services_selected))->select('name')->get();
            $g->passes = $passes;
            $g->services = $services;
        }
        return $gymBranchs;
    }


    public function create_gym_branch($objectSave)
    {
        $rowCreated = GymBranch::create($objectSave);
        $response = GymBranch::where('id', $rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function update_gym_branch($id, $objectSave)
    {
        $update = GymBranch::find($id)->update($objectSave);
        $response = GymBranch::where('id', $id)->first();
        return $response;
    }

    public function delete_gym_branch($id)
    {
        $response = GymBranch::find($id)->delete();

        return $response;
    }

    public function get_near_gym_branchs($latitude, $longitude)
    {
        $results = DB::select(DB::raw("SELECT ( 6371000 * acos( cos( radians(" . $latitude . ") ) * cos( radians(`latitude`) ) * cos( radians( `longitude` ) - radians(" . $longitude . ") ) + sin( radians(" . $latitude . ") ) * sin(radians(`latitude`)) ) ) AS dist, gym_branch.* FROM
        gym_branch HAVING dist < 500 "));

        return $results;
    }


    public function get_app_filter_gym_brachs($gym_branch_text, $text_search, $type, $from, $to, $any_timetable, $price_range_low, $price_range_high, $amenities, $parking)
    {
        $gym_branchs =  GymBranch::where('gym_branch.active', 1)
        ->leftJoin('cities', 'cities.id', '=', 'gym_branch.city')
        ->leftJoin('provinces', 'provinces.id', '=', 'gym_branch.province')
        ->leftJoin('cantones', 'cantones.id', '=', 'gym_branch.canton')
        // ->orWhere('gym_branch.type', $type)
        ->orWhere('gym_branch.commercial_name', 'like', '%' . $gym_branch_text . '%')
        ->where('gym_branch.parking', $parking)
        ->orWhere('cities.name', 'like', '%' . $text_search . '%')
        ->orWhere('provinces.name', 'like', '%' . $text_search . '%')
        ->orWhere('cantones.name', 'like', '%' . $text_search . '%')
        ->orWhere('gym_branch.sector', 'like', '%' . $text_search . '%');

        $gym_branchs = $gym_branchs->when($parking == 1, function ($q) {
            return $q->where('gym_branch.parking', 1);
        });
        
        if (!$any_timetable) {
            $gym_branchs = $gym_branchs->orWhere(function ($query) use ($from, $to) {
                $query->where('gym_branch.week_from', '<=', $from)
                    ->where('gym_branch.week_to_afternoon', '>=', $to);
            })
                ->orWhere(function ($query) use ($from, $to) {
                    $query->where('gym_branch.saturday_to', '<=', $from)
                        ->where('gym_branch.saturday_to_afternoon', '>=', $to);
                })
                ->orWhere(function ($query) use ($from, $to) {
                    $query->where('gym_branch.freeday_from', '<=', $from)
                        ->where('gym_branch.freeday_to_afternoon', '>=', $to);
                });
        }

        $passes = Passe::where('active', 1)
            ->where('train_now_price', '>=', $price_range_low)
            ->where('train_now_price', '<=', $price_range_high);

        $gym_branchs = $gym_branchs->joinSub($passes, 'passes', function ($join) {
            $join->on('passes.branch_id', '=', 'gym_branch.id');
        });

        $gym_branchs = $gym_branchs->whereRaw("JSON_CONTAINS(gym_branch.amenities, ?)", [$amenities]);
        $gym_branchs = $gym_branchs->select('gym_branch.id','gym_branch.commercial_name','gym_branch.main_image','gym_branch.rating_value')->get();

        return $gym_branchs;
    }
}
