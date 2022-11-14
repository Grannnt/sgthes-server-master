<?php

namespace App\Http\Controllers;

use App\Models\AnswerKey;
use App\Models\Student;
use App\Models\StudentAnswerSheetInfo;
use Illuminate\Http\Request;

class ScoreBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data_series_name = [];
        $data_series_value = [];

        $school_year_id = $request->school_year_id;
        $section_id = $request->section_id;
        $subject_id = $request->subject_id;

        $answerKeys = AnswerKey::whereNull('deleted_at');

        if ($school_year_id) {
            $answerKeys->where('school_year_id', $school_year_id);
        }
        if ($section_id) {
            $answerKeys->where('section_id', $section_id);
        }
        if ($subject_id) {
            $answerKeys->where('subject_id', $subject_id);
        }

        if (auth()->user()->user_role_id != 1) {
            $answerKeys->where('created_by', auth()->user()->id);
        }

        $answerKeys = $answerKeys->orderBy('id');
        $answerKeys = $answerKeys->get();

        foreach ($answerKeys as $answerKeyskey => $answerKeysvalue) {
            $data_series_name[] = [
                'name'                  => $answerKeysvalue->answer_key_name,
                'number_of_question'    => $answerKeysvalue->number_of_question
            ];
        }

        $students = Student::whereNull('deleted_at');
        if (auth()->user()->user_role_id != 1) {
            $students->where('created_by', auth()->user()->id);
        }

        if ($school_year_id) {
            $students->where('school_year_id', $school_year_id);
        }
        if ($section_id) {
            $students->where('section_id', $section_id);
        }

        $students =  $students->orderBy('id');
        $students =  $students->get();

        foreach ($students as $students_key => $students_value) {
            $data_info = [];
            foreach ($answerKeys as $answerKeyskey => $answerKeysvalue) {
                $studentAnswerSheetInfo = StudentAnswerSheetInfo::select('student_answer_sheet_infos.*')
                    ->with(['student_answer_sheet_result' => function ($query) {
                        $query->orderBy('id', 'desc');
                    }])
                    ->where('student_id', $students_value->id)
                    ->whereNull('deleted_at')
                    ->where('answer_key_id', $answerKeysvalue->id)
                    ->studentAnswerSheet();


                if ($school_year_id) {
                    $studentAnswerSheetInfo->where('school_year_id', $school_year_id);
                }
                if ($section_id) {
                    $studentAnswerSheetInfo->where('section_id', $section_id);
                }
                if ($subject_id) {
                    $studentAnswerSheetInfo->where('subject_id', $subject_id);
                }

                $data_info[] = $studentAnswerSheetInfo->first();;
            }

            $data_series_value[] = [
                'name'      => $students_value->name,
                'lrn'       => $students_value->lrn,
                'data_info' => $data_info
            ];
        }

        $data = [
            'data_series_name'  => $data_series_name,
            'data_series_value' => $data_series_value
        ];

        $ret = [
            'success'   => true,
            'data'      => $data
        ];

        return response()->json($ret, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
