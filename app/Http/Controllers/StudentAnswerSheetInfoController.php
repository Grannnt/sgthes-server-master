<?php

namespace App\Http\Controllers;

use App\Models\StudentAnswerSheetInfo;
use Illuminate\Http\Request;

class StudentAnswerSheetInfoController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->page_title = "Student Answer Sheet Info";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ret = [
            'success'   => true,
            'data'      => []
        ];

        if ($request->requestFrom == 'scanner' && $request->scancode != '') {
            $studentAnswerSheetInfo = StudentAnswerSheetInfo::with(
                'student_answer_sheet',
                'student',
                'student.school_year',
                'student.section',
                'student.subject'
            );
            $studentAnswerSheetInfo->where('code', $request->scancode);
            $table_columns = new StudentAnswerSheetInfo();
            $table_columns = $table_columns->getTableColumns();

            if ($request->sort_order != '' && in_array($request->sort_field, $table_columns)) {
                $studentAnswerSheetInfo->orderBy($request->sort_field, $request->sort_order == 'ascend' ? 'asc' : 'desc');
            }

            if ($request->page) {
                $studentAnswerSheetInfo = $studentAnswerSheetInfo->paginate(10);
            } else {
                $studentAnswerSheetInfo = $studentAnswerSheetInfo->get();
            }

            $ret = [
                'success'   => true,
                'data'      => $studentAnswerSheetInfo
            ];
        }

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
     * @param  \App\Models\StudentAnswerSheetInfo  $studentAnswerSheetInfo
     * @return \Illuminate\Http\Response
     */
    public function show(StudentAnswerSheetInfo $studentAnswerSheetInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentAnswerSheetInfo  $studentAnswerSheetInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentAnswerSheetInfo $studentAnswerSheetInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentAnswerSheetInfo  $studentAnswerSheetInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentAnswerSheetInfo $studentAnswerSheetInfo)
    {
        //
    }
}
