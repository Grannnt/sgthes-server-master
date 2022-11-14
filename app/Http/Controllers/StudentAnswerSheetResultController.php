<?php

namespace App\Http\Controllers;

use App\Models\StudentAnswerSheetInfo;
use App\Models\StudentAnswerSheetResult;
use Illuminate\Http\Request;

class StudentAnswerSheetResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ret = $this->response_message();

        $student_answer_sheet_id    = $request->student_answer_sheet_id;
        $score                      = $request->score;
        $lrn                        = $request->lrn;

        $school_year_exits = StudentAnswerSheetInfo::select('student_answer_sheet_infos.id')->where('student_answer_sheet_id', $student_answer_sheet_id)
            ->where('lrn', $lrn)
            ->studentJoin()
            ->first();

        if ($school_year_exits) {
            $studentAnswerKeyResult = StudentAnswerSheetResult::create([
                'student_answer_sheet_info_id'  => $school_year_exits->id,
                'score'                         => $score
            ]);

            if ($studentAnswerKeyResult) {
                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data inserted successfully!'
                ]);
            }
        } else {
            $ret = $this->response_message([
                'success'       => false,
                'description'   => 'Data already exist!'
            ]);
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentAnswerSheetResult  $studentAnswerSheetResult
     * @return \Illuminate\Http\Response
     */
    public function show(StudentAnswerSheetResult $studentAnswerSheetResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentAnswerSheetResult  $studentAnswerSheetResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentAnswerSheetResult $studentAnswerSheetResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentAnswerSheetResult  $studentAnswerSheetResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentAnswerSheetResult $studentAnswerSheetResult)
    {
        //
    }
}
