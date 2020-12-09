<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Evaluation extends Model
{
    protected $table = 'evaluations';
    protected $fillable = ['profeessor_id','level_id','subject_id','type_id','name','description','path','deadline_date','video_lesson_id'];

    public function get_gym_branchs()
    {
        $result = DB::table('gym_branch')->get();
        return $result;
    }

    public function create_gym_branch($objectSave)
    {
        $rowCreated = DB::table('gym_branch')->create([
            'evaluation_id' => $request->input('evaluation_id'),
            'user_id' => $user_id,
            'note' => $request->input('note'),
            'path_task' => $url,
            'status' => '2'
           ]);

       $response = EvaluationUser::where('id',$rowCreated->id)
            ->select('evaluation_user.id as evalUserId','evaluation_user.*')
            ->first();

       return $response;
    }

    public function update_gym_branch($objectSave)
    {
        $update = DB::table('gym_branch')->find($objectSave->id)->update([$objectSave]);
        $response = DB::table('gym_branch')->where('id',$id)->first();

        return $response;
    }

    public function evaluationsByUsersssss($id)
    {
        $evaluations = DB::table('evaluations')
                ->select('evaluations.id as evaluation_id','evaluations.*','users.name as professorName','users.image_profile as imageProfile')
                ->join('users', 'evaluations.professor_id', '=', 'users.id')
                ->get();

        $evaluation_user = DB::table('evaluation_user')
            ->where('user_id', $id)
            ->select('evaluation_user.id as evalUserId','evaluation_user.*')
            ->get();

        $result = $evaluations->merge($evaluation_user);
        $result = $result->groupBy('evaluation_id');

        return $result;
    }

    public function evaluationsByUser($subject_id, $level_id, $id)
    {
        $evaluations = DB::table('evaluations')
                ->where('evaluations.subject_id', $subject_id)
                ->where('evaluations.level_id', $level_id)
                ->select('evaluations.id as evaluation_id',
                         'evaluations.*',
                         'users.name as professorName',
                         'users.image_profile as imageProfile',
                         'video_lesson.path_documentation_1 as docFileA',
                         'video_lesson.path_documentation_2 as docFileB',
                         'video_lesson.url_documentation as docUrl'

                        )
                ->leftJoin('users', 'evaluations.professor_id', '=', 'users.id')
                ->leftJoin('video_lesson', 'evaluations.video_lesson_id', '=', 'video_lesson.id')
                ->get();

        foreach($evaluations as $ev){
            $evaluation_user = DB::table('evaluation_user')
                ->where('user_id', $id)
                ->where('evaluation_id',$ev->id)
                ->select('evaluation_user.id as evalUserId','evaluation_user.*')
                ->first();
                $ev->evalUser = $evaluation_user;
        }

        return $evaluations;
    }



    public function save_evaluation($request, $path_eval, $professor_id)
    {

        $id = $request->input('evaluation_id');
        $level_id = $request->input('level_id');
        $subject_id = $request->input('subject_id');
        $video_lesson_id = $request->input('id');
        $type_id = $request->input('type_id');
        $deadline_date = $request->input('deadline_date');
        $evaluation_name = $request->input('evaluation_name');

        $response = null;
        $path = ($path_eval) ? "path"  : '';
        $name = ($evaluation_name) ? "name"  : '';


        if($id == 'null'){

            $object_create = [
                $path => $path_eval,
                $name => $evaluation_name,
                'professor_id' => $professor_id,
                'level_id' => $level_id,
                'subject_id' => $subject_id,
                'video_lesson_id' => $video_lesson_id,
                'type_id' => $type_id,
                'deadline_date' => $deadline_date
            ];

            $response = Evaluation::create($object_create); //$id

        }else{

          $object_update = [
            $path => $path_eval,
            $name => $evaluation_name,
            'professor_id' => $professor_id,
            'type_id' => $type_id,
            'deadline_date' => $deadline_date

          ];

          $response = Evaluation::find($id)->update($object_update);

        }

        return $response;
    }

}
