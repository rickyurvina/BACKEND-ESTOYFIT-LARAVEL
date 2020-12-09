<?php

namespace App\Http\Controllers\API;


use App\Models\Amenity;
use App\Models\Cantones;
use App\Models\Parroquias;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Evaluation;
use App\Models\EvaluationUser;
use App\User;
use App\Models\GymBranch;
use App\Models\GalleryGymBranch;
use App\Models\ReviewGymBranch;
use App\Models\Passe;
use App\Models\Cities;
use App\Models\Provinces;
use App\Models\Service;
use App\Models\SuggestionTag;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EvaluationController extends Controller {

    public function get_gym_branchs(Request $request) {
        $gym_branch = new GymBranch;
        $gym_id = $request->input('gym_id');
        $response = $gym_branch->get_gym_branchs($gym_id);

        return response()->json([
                    "data" => $response,
                    "gymId" => $gym_id
        ]);
    }

    public function get_all_gym_branchs(Request $request) {
        $gym_branch = new GymBranch;
        $response = $gym_branch->get_all_gym_branchs();

        return response()->json([
                    "data" => $response,
        ]);
    }

    

    public function get_gym_branch_data(Request $request) {
        $gym = new GymBranch;
        $user = new User;
        $cities = new Cities;
        $provinces=new Provinces;
        $cantones=new Cantones;
        $parroquias= new Parroquias;
        $service = new Service;
        $amenitie= new Amenity;
        $id = $request->input('id');
        $response = $gym->get_gym_branch_data($id);
        $users = $user->get_gym_branch_admin_users($id);
        $cities = $cities->get_cities();
        $provinces=$provinces->get_provinces();
        $cantones=$cantones->get_cantones();
//        $parroquias=$parroquias->get_parroquias();
        $services = $service->get_services();
        $amenities=$amenitie->get_amenities();

        return response()->json([
                    "data" => $response,
                    "users" => $users,
                    "cities" => $cities,
                    'provinces'=>$provinces,
                    'cantones'=>$cantones,
//                    'parroquias'=>$parroquias,
                    "services" => $services,
                     "amenities"=>$amenities
        ]);
    }

    public function save_gym_branch(Request $request) {
        $gym_branch = new GymBranch;
        $gym_user = new User;
        $sugestion_tag = new SuggestionTag;
        $id = $request->input('id');
        $user_id = $request->input('user_id');
        $commercial_name = $request->input('commercial_name');
        $ruc = $request->input('ruc');
        $email = $request->input('email');
        $name = $request->input('name');
        $password = $request->input('password');
        $url_file = $request->input('url_file');
        $resume = $request->input('resume');

        // $validateFields = $request->validate([
        //     'name' => ['required'],
        //     'email' => ['required', 'unique:users'],
        // ]);
        // if(!$validateFields){
        //     return response()->json([
        //         "error" => $validateFields,
        //     ]);
        // }


        $objectSave = [
            'gym_id' => Auth::user()->gym_id,
            'commercial_name' => $commercial_name,
            'ruc' => $ruc,
            'email' => $email,
            'password' => $password,
            'main_image' => $url_file,
            'resume' => $resume,
            'services_selected' => json_encode([0]),
            'amenities' => json_encode([0]),
        ];

        if ($id != 'null') {

            $response = $gym_branch->update_gym_branch($id, $objectSave);
            $objectUserSave = [
                'email' => $email,
                'name' => $name,
            ];

            $response = $gym_user->update_gym_user($user_id, $objectUserSave);
        } else {
            $branch_id = $gym_branch->create_gym_branch($objectSave);

            $objectUserSave = [
                'gym_id' => Auth::user()->gym_id,
                'branch_id' => $branch_id,
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'role_id' => '3',
                'lead' => '1'
            ];
            $response = $gym_user->create_gym_user($objectUserSave);
        }

        $id_row = ($id != 'null') ? $id : $branch_id;
        $objectSaveSuggestionTag = [
            'id_row' => $id_row,
            'type' => '1',
            'name' => $commercial_name,
            'categories' => json_encode([0])
        ];

        $resp_suggestion_tag = $sugestion_tag->save_sugestion_tag($objectSaveSuggestionTag);

        $data = $gym_branch->get_gym_branchs(Auth::user()->gym_id);

        return response()->json([
                    "error" => "",
                    "response" => $response,
                    "data" => $data
        ]);
    }

    public function save_gym_branch_data(Request $request) {
        $gym_branch = new GymBranch;
        $gym_branch = new GymBranch;
        $sugestion_tag = new SuggestionTag;

        $id = $request->input('id');
        $active = $request->input('active');
        $commercial_name = $request->input('commercial_name');
        $ruc = $request->input('ruc');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $mobile = $request->input('mobile');
        $description = $request->input('description');
        $amenities = $request->input('amenities');
        $services_selected = $request->input('services_selected');
        $parking=$request->input('parking');
        $city = $request->input('city');
        $province = $request->input('province');
        $canton = $request->input('canton');//
        $sector = $request->input('sector');
        $main_street = $request->input('main_street');
        $intersection = $request->input('intersection');
        $numeration = $request->input('numeration');
        $reference = $request->input('reference');
        $website = $request->input('website');
        $facebook = $request->input('facebook');
        $instagram = $request->input('instagram');
        $youtube = $request->input('youtube');
        $main_image = $request->input('main_image');
        $banner_image = $request->input('banner_image');
        $is_image = $request->input('is_image');
        $week_from = $request->input('week_from');
        $week_to = $request->input('week_to');
        $week_from_afternoon = $request->input('week_from_afternoon');
        $week_to_afternoon = $request->input('week_to_afternoon');
        $saturday_from = $request->input('saturday_from');
        $saturday_to = $request->input('saturday_to');
        $saturday_from_afternoon = $request->input('saturday_from_afternoon');
        $saturday_to_afternoon = $request->input('saturday_to_afternoon');
        $freeday_from = $request->input('freeday_from');
        $freeday_to = $request->input('freeday_to');
        $freeday_from_afternoon = $request->input('freeday_from_afternoon');
        $freeday_to_afternoon = $request->input('freeday_to_afternoon');

        $exp = explode(',', $services_selected);
        // json_encode(array_map('strval', $data));

        $objectSave = [
            'active' => $active,
            'commercial_name' => $commercial_name,
            'ruc' => $ruc,
            'email' => $email,
            'phone' => $phone,
            'mobile' => $mobile,
            'description' => $description,
            'city' => $city,
            'province'=>$province,
            'canton'=>$canton,//
            'sector' => $sector,
            'main_street' => $main_street,
            'intersection' => $intersection,
            'numeration' => $numeration,
            'reference' => $reference,
            'website' => $website,
            'facebook' => $facebook,
            'instagram' => $instagram,
            'youtube' => $youtube,
            'services_selected' => json_encode(array_map('intval', explode(',', $services_selected))),
           'parking'=>$parking,
            'amenities' => json_encode(array_map('intval', explode(',', $amenities))),
            'main_image' => $main_image,
            'banner_image' => $banner_image,
            'is_image' => $is_image,
            'week_from' => Carbon::parse($week_from),
            'week_to' => Carbon::parse($week_to),
            'week_from_afternoon' => Carbon::parse($week_from_afternoon),
            'week_to_afternoon' => Carbon::parse($week_to_afternoon),
            'saturday_from' => Carbon::parse($saturday_from),
            'saturday_to' => Carbon::parse($saturday_to),
            'saturday_from_afternoon' => Carbon::parse($saturday_from_afternoon),
            'saturday_to_afternoon' => Carbon::parse($saturday_to_afternoon),
            'freeday_from' => Carbon::parse($freeday_from),
            'freeday_to' => Carbon::parse($freeday_to),
            'freeday_from_afternoon' => Carbon::parse($freeday_from_afternoon),
            'freeday_to_afternoon' => Carbon::parse($freeday_to_afternoon),
        ];

        $objectSaveSuggestionTag = [
            'id_row' => $id,
            'type' => '1',
            'name' => $commercial_name,
            'categories' => json_encode([0])
        ];

        $objectSaveSuggestionTagSector = [
            'name' => $sector,
            'categories' => json_encode([1])
        ];

        $resp_suggestion_tag = $sugestion_tag->save_sugestion_tag($objectSaveSuggestionTag);
        $resp_suggestion_tag_sector = $sugestion_tag->save_sugestion_tag_sector($objectSaveSuggestionTagSector);


        $response = $gym_branch->update_gym_branch($id, $objectSave);

        return response()->json([
                    "error" => "",
                    "response" => $response,
                    "decode" => explode(',', $services_selected)
        ]);
    }

    public function delete_gym_branch(Request $request) {
        $gym_branch = new GymBranch;
        $id = $request->input('id');
        $response = $gym_branch->delete_gym_branch($id);
        $data = $gym_branch->get_gym_branchs(Auth::user()->gym_id);

        return response()->json([
                    "error" => "",
                    "response" => $response,
                    "data" => $data
        ]);
    }

    public function update_gallery_gym_branch(Request $request) {
        $gallery_gym_branch = new GalleryGymBranch;
        $id = $request->input('id');
        $arr_objects = json_decode($request->input('arr_objects'));
        $response = $gallery_gym_branch->update_gallery_gym_branch($id, $arr_objects);
        $data = $gallery_gym_branch->get_gallery_gym_branch($id);

        return response()->json([
                    "error" => "",
                    "response" => $response,
                    "data" => $data,
                    "arr" => $arr_objects
        ]);
    }

    public function update_review_gym_branch(Request $request, $id) {
        $review_gym_branch = new ReviewGymBranch;
        $id = $request->input('id');
        $arr_objects = json_decode($request->input('arr_objects'));
        $response = $review_gym_branch->update_review_gym_branch($id, $arr_objects);
        $data = $review_gym_branch->get_review_gym_branch($id);
        return response()->json([
                    "error" => "",
                    "response" => $response,
                    "data" => $data,
                    "arr" => $arr_objects
        ]);
    }

    public function get_reviews() {
        $review_gym_branch = new ReviewGymBranch;
        $data = $review_gym_branch->get_reviews();
        return response()->json([
            "data" => $data,
        ]);
    }
    public function delete_review(Request $request) {
        $review = new ReviewGymBranch;
        $id = $request->input('id');
        $response = $review->delete_review($id);
        $data = $review->get_reviews();

        return response()->json([
            "error" => "",
            "response" => $response,
            "data" => $data
        ]);
    }

    public function get_review_gym_branch(Request $request) {
        $review_gym_branch = new ReviewGymBranch;
        $id = $request->input('id');
        $data = $review_gym_branch->get_review_gym_branch($id);

        return response()->json([
                    "data" => $data,
        ]);
    }

    public function get_gallery_gym_branch(Request $request) {
        $gallery_gym_branch = new GalleryGymBranch;
        $id = $request->input('id');
        $data = $gallery_gym_branch->get_gallery_gym_branch($id);

        return response()->json([
                    "error" => "",
                    // "response" => $response,
                    "data" => $data,
        ]);
    }

    public function get_gym_branchs_front(Request $request, $city) {
        $gym_branch = new GymBranch;
        $response = $gym_branch->get_gym_branchs_front($city);

        return response()->json([
                    "data" => $response
        ]);
    }

    public function get_gym_branchs_by_activity_front(Request $request, $activity, $city) {
        $gym_branch = new GymBranch;
        $response = $gym_branch->get_gym_branchs_by_activity_front($activity, $city);

        return response()->json([
                    "data" => $response
        ]);
    }

    public function get_gym_branchs_by_filter_front(Request $request) {
        $suggestion_id = $request->input('suggestion_id');
        $text_suggestion = $request->input('text_suggestion');
        $text_suggestion_two = $request->input('text_suggestion_two');
        $filter_field = '';
        $gym_branch = new GymBranch;
        $passe = new Passe;

        switch ($suggestion_id) {
            case 1:
                $filter_field = "commercial_name";
                break;
            case 2:
                $filter_field = "sector";
                break;
            case 3:
                $filter_field = "cities.name";
                break;
        }

        if (($suggestion_id == 1) || ($suggestion_id == 2) || ($suggestion_id == 3)) {
            $response = $gym_branch->get_gym_branchs_by_filter_front($filter_field, $text_suggestion);
        }

        if ($suggestion_id == 4) {
            $response = $gym_branch->get_gym_branchs_by_activity_city_front($text_suggestion, $text_suggestion_two);
        }

        if ($suggestion_id == 5) {
            $response = $passe->get_hotdeal_passes_by_city_front($text_suggestion);
        }

        if ($suggestion_id == 6) {
            $response = $passe->get_hotdeal_passes_by_activity_front($text_suggestion);
        }

        return response()->json([
                    "data" => $response,
                    "filter_field" => $filter_field,
                    "text" => $text_suggestion
        ]);
    }

    public function get_gym_branch_data_front(Request $request, $id) {
        $gym_branch = new GymBranch;
        $gallery_gym_branch = new GalleryGymBranch;
        $passe = new Passe;
        $review_gym_branch = new ReviewGymBranch;
        $response = $gym_branch->get_gym_branch_data($id);
        $gallery = $gallery_gym_branch->get_gallery_gym_branch($id);
        $passes = $passe->get_passes_gym_branch_front($id);
        $review = $review_gym_branch->get_review_gym_branch($id);
        $average = $review_gym_branch->get_average_rating_gym_branch($id);
        return response()->json([
                    "data" => $response,
                    "gallery" => $gallery,
                    "passes" => $passes,
                    'review' => $review,
                    'average' => $average
        ]);
    }

    public function get_gym_branch_data_front_light(Request $request, $id) {
        $gym_branch = new GymBranch;
        $passe = new Passe;
        $review_gym_branch = new ReviewGymBranch;
        $response = $gym_branch->get_gym_branch_data($id);

        $passes = $passe->get_passes_gym_branch_validated_front($id);
        $average = $review_gym_branch->get_average_rating_gym_branch($id);
        return response()->json([
                    "data" => $response,
                    'average' => $average,
                    'passes' => $passes
        ]);
    }

     public function get_cities(Request $request) {
        $cities = new Cities;
        $response = $cities->get_cities();

        return response()->json([
                    "cities" => $response
        ]);
    }

    public function get_services(Request $request) {
        $service = new Service;
        $response = $service->get_services();

        return response()->json([
                    "services" => $response
        ]);
    }

    // MOBILE API.

    public function get_near_gym_brachs(Request $request) {
        $gym_branch = new GymBranch;
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $response = $gym_branch->get_near_gym_branchs($latitude, $longitude);

        return response()->json([
            "data" => $response
        ]);
    }

    public function get_app_filter_gym_brachs(Request $request) {
        $gym_branch = new GymBranch;
        $gym_branch_text = $request->input('gym_branch_text');
        $text_search = $request->input('text_search');
        $type = $request->input('type'); 
        $from = Carbon::parse($request->input('from'))->format('H:m:s');
        $to = Carbon::parse($request->input('to'));
        $any_timetable = $request->input('any_timetable');
        $price_range_low = $request->input('price_range_low');
        $price_range_high = $request->input('price_range_high');
        $amenities = $request->input('amenities');
        $parking = $request->input('parking');
        $response = $gym_branch->get_app_filter_gym_brachs($gym_branch_text, $text_search, $type, $from, $to, $any_timetable, $price_range_low, $price_range_high, $amenities, $parking);

        return response()->json([
            "data" => $response
        ]);
    }




}
