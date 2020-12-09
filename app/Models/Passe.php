<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Passe extends Model {

    protected $table = 'passes';
    protected $fillable = [
        'id',
        'gym_id',
        'branch_id',
        'name',
        'description',
        'conditions',
        'original_price',
        'discount',
        'type_discount',
        'discount_value',
        'train_now_price',
        'days_for_validate',
        'expiration_date',
        'color',
        'sort',
        'n_avaible',
        'valid_from',
        'valid_to',
        'train_now_black',
        'hot_deal',
        'for_turist',
        'type',
        'category',
        'commission',
        'commission_fix_price',
        'active'
    ];

    public function get_passes() {
        $result = DB::table('passes')
                ->leftJoin('type_passes', 'passes.type', '=', 'type_passes.id')
                ->leftJoin('categories', 'passes.category', '=', 'categories.id')
                ->leftJoin('gym_branch', 'passes.branch_id', '=', 'gym_branch.id')
                ->leftJoin('cities', 'gym_branch.city', '=', 'cities.id')
                ->leftJoin('gym', 'gym_branch.gym_id', '=', 'gym.id')
                ->select('passes.*', 'type_passes.name as typeName', 'categories.name as categoryName', 'cities.name as cityName', 'gym_branch.commercial_name as gymName')
                ->get();
        return $result;
    }

    public function get_passes_gym_branch_front($id) {
        $result = DB::table('passes')
                ->where('passes.active', '1')
                ->where('passes.branch_id', $id)
                ->leftJoin('type_passes', 'passes.type', '=', 'type_passes.id')
                ->leftJoin('categories', 'passes.category', '=', 'categories.id')
                ->leftJoin('gym_branch', 'passes.branch_id', '=', 'gym_branch.id')
                ->leftJoin('cities', 'gym_branch.city', '=', 'cities.id')
                ->leftJoin('gym', 'gym_branch.gym_id', '=', 'gym.id')
                ->select('passes.*', 'type_passes.name as typeName', 'categories.name as categoryName', 'cities.name as cityName', 'gym.commercial_name as gymName')
                ->get();
        return $result;
    }

    public function get_hotdeal_passes_by_activity_front($text_suggestion) {
        $service = DB::table('services')->where("name", $text_suggestion)->first();
        $service_id = strval($service->id);
        $result = DB::table('passes')
                ->where("passes.hot_deal", 1)
                ->whereRaw("JSON_CONTAINS(gym_branch.services_selected, ?)", [$service_id])
                ->leftJoin('gym_branch', 'gym_branch.id', '=', 'passes.branch_id')
                ->leftJoin('type_passes', 'passes.type', '=', 'type_passes.id')
                ->leftJoin('categories', 'passes.category', '=', 'categories.id')
                ->select('passes.*', 'gym_branch.commercial_name as gymBranchName', 'gym_branch.main_image as mainImage', 'type_passes.name as typeName', 'categories.name as categoryName')
                ->get();
        return $result;
    }

    public function get_hotdeal_passes_by_city_front($text_suggestion) {
        $result = DB::table('passes')
                ->where("passes.active", 1)
                ->where("passes.hot_deal", 1)
                ->where("cities.name", $text_suggestion)
                ->leftJoin('gym_branch', 'gym_branch.id', '=', 'passes.branch_id')
                ->join('cities', 'cities.id', '=', 'gym_branch.city')
                ->leftJoin('type_passes', 'passes.type', '=', 'type_passes.id')
                ->leftJoin('categories', 'passes.category', '=', 'categories.id')
                ->select('passes.*', 'gym_branch.commercial_name as gymBranchName', 'gym_branch.main_image as mainImage', 'type_passes.name as typeName', 'categories.name as categoryName', 'cities.name as cityName')
                ->get();
        return $result;
    }

    public function get_passes_hotdeal_promotion_by_city_front($text) {
        $result = DB::table('passes')
                ->where('passes.active', '1')
                ->where('passes.hot_deal', '1')
                ->where("cities.name", 'like', '%' . $text . '%')
                ->leftJoin('type_passes', 'passes.type', '=', 'type_passes.id')
                ->leftJoin('categories', 'passes.category', '=', 'categories.id')
                ->leftJoin('gym_branch', 'passes.branch_id', '=', 'gym_branch.id')
                ->leftJoin('cities', 'gym_branch.city', '=', 'cities.id')
                ->leftJoin('gym', 'gym_branch.gym_id', '=', 'gym.id')
                ->select('passes.*', 'type_passes.name as typeName', 'categories.name as categoryName', 'cities.name as cityName', 'gym.commercial_name as gymName')
                ->get();

        return $result;
    }

    public function get_hot_deal_passes($city) {
        $result = DB::table('passes')
                ->where('passes.active', '1')
                ->where('passes.hot_deal', '1')
                ->leftJoin('type_passes', 'passes.type', '=', 'type_passes.id')
                ->leftJoin('categories', 'passes.category', '=', 'categories.id')
                ->leftJoin('gym_branch', 'passes.branch_id', '=', 'gym_branch.id')
                ->leftJoin('cities', 'gym_branch.city', '=', 'cities.id')
                ->select('passes.*', 'gym_branch.commercial_name as gymBranchName', 'gym_branch.main_image as mainImage', 'type_passes.name as typeName', 'categories.name as categoryName', 'cities.name as cityName');

        if ($city != 0) {
            $result->where('gym_branch.city', $city);
        }

        return $result->get();
    }

    public function get_passe_data($id) {
        $result = Passe::where('id', $id)->first();
        return $result;
    }

    public function get_passes_front() {
        $result = DB::get('passe');
        return $result;
    }

    public function create_passe($objectSave) {
        $rowCreated = Passe::create($objectSave);
        $response = Passe::where('id', $rowCreated->id)->first();
        return $rowCreated->id;
    }

    public function update_passe($id, $objectSave) {
        $update = Passe::find($id)->update($objectSave);
        $response = Passe::where('id', $id)->first();
        return $response;
    }

    public function delete_passe($id) {
        $response = Passe::find($id)->delete();

        return $response;
    }

    public function active_passe($id, $action) {
        $update = Passe::find($id)->update(['active' => $action]);

        return $update;
    }

    public function get_passes_gym_branch_validated_front($id) {
        $date = date('Y-m-d');
        $result = DB::table('passes')
                ->where('passes.active', '1')
                ->where('passes.branch_id', $id)
                // ->where('passes.valid_from', '<=', $date)
                // ->where('passes.valid_to', '>=', $date)
                ->where('passes.n_avaible', '>=', 1)
                ->leftJoin('type_passes', 'passes.type', '=', 'type_passes.id')
                ->leftJoin('categories', 'passes.category', '=', 'categories.id')
                ->leftJoin('gym_branch', 'passes.branch_id', '=', 'gym_branch.id')
                ->leftJoin('cities', 'gym_branch.city', '=', 'cities.id')
                ->leftJoin('gym', 'gym_branch.gym_id', '=', 'gym.id')
                ->select('passes.*', 'type_passes.name as typeName', 'categories.name as categoryName', 'cities.name as cityName', 'gym.commercial_name as gymName')
                ->get();
        return $result;
    }

}
