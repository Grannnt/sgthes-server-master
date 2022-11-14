<?php

namespace App\Http\Controllers;

use App\Models\StudentAnswerSheet;
use App\Models\StudentAnswerSheetInfo;
use Illuminate\Http\Request;

class StudentAnswerSheetController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->page_title = "Student Answer Sheet";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $studentAnswerSheets = StudentAnswerSheet::select(
            'student_answer_sheets.*',
            'answer_key_name',
            'school_year_from',
            'school_year_to',
            'section',
            'subject',
            'semester',
            'number_of_question',
            'choices_type'
        )
            ->with(['student_answer_sheet_info' => function ($query) use ($request) {
                if ($request->from == "scanner") {
                    if ($request->lrn != "") {
                        $query->where('lrn', $request->lrn)
                            ->studentJoin()
                            ->first();
                    }
                }
            }, 'teacher', 'answer_key', 'answer_key.user', 'answer_key.answer_key_info'])
            ->where(function ($query) use ($request) {
                if ($request->search) {
                    $query->where('code', 'LIKE', "%$request->search%");
                    $query->where('answer_key_name', 'LIKE', "%$request->search%");
                    $query->where('school_year_from', 'LIKE', "%$request->search%");
                    $query->where('school_year_to', 'LIKE', "%$request->search%");
                    $query->where('section', 'LIKE', "%$request->search%");
                    $query->where('subject', 'LIKE', "%$request->search%");
                }
            })
            ->answerKey()
            ->schoolYear()
            ->section()
            ->subject();

        if (auth()->user()->user_role_id != 1) {
            $studentAnswerSheets->where('student_answer_sheets.created_by', auth()->user()->id);
        }

        $table_columns = new StudentAnswerSheet();
        $table_columns = $table_columns->getTableColumns();

        if ($request->sort_order != '' && in_array($request->sort_field, $table_columns)) {
            $studentAnswerSheets->orderBy($request->sort_field, $request->sort_order == 'ascend' ? 'asc' : 'desc');
        }

        if ($request->page) {
            $studentAnswerSheets = $studentAnswerSheets->paginate(10);
        } else {
            if ($request->from == "scanner") {
                if ($request->student_answer_sheet_id != "") {
                    $studentAnswerSheets = $studentAnswerSheets->where('student_answer_sheets.id', $request->student_answer_sheet_id)->first();
                }
            } else {
                $studentAnswerSheets = $studentAnswerSheets->orderBy('student_answer_sheets.created_at', 'asc')->get();
            }
        }

        $ret = [
            'success'   => true,
            'data'      => $studentAnswerSheets
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
        $ret = $this->response_message();

        $data = $request->validate([
            'answer_key_id'     => 'required',
            'school_year_id'    => 'required',
            'section_id'        => 'required',
            'subject_id'        => 'required',
        ]);

        $code = StudentAnswerSheet::where('created_by', auth()->user()->id)
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->first();

        $last_code = 1;

        if ($code) {
            $last_code = explode("-", $code->code)[1] + 1;
        }

        $data += ['code' => $this->generate_number(6, sprintf("%04d", $last_code))];

        $existStudentAnswerSheet = StudentAnswerSheet::where('answer_key_id', $request->answer_key_id)
            ->where('school_year_id', $request->school_year_id)
            ->where('section_id', $request->section_id)
            ->where('subject_id', $request->subject_id)
            ->whereNull('deleted_at')
            ->count();

        if ($existStudentAnswerSheet == 0) {
            $studentAnswerSheets = StudentAnswerSheet::create($data);

            if ($request->info) {
                foreach ($request->info as $key => $value) {
                    $info_code = StudentAnswerSheetInfo::where('student_answer_sheet_id', $studentAnswerSheets->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    $last_info_code = 1;

                    if ($info_code) {
                        $last_info_code = explode("-", $info_code->code)[1] + 1;
                    }

                    $studentAnswerSheets->student_answer_sheet_info()->create([
                        'student_id'    => $value['student_enrollee_id'],
                        'code'          => $this->generate_number(6, sprintf("%04d", $last_info_code))
                    ]);
                }
            }

            $ret = $this->response_message([
                'success'       => true,
                'description'   => 'Data inserted successfully!',
                'id'            => $studentAnswerSheets->id
            ]);
        } else {
            $ret = $this->response_message([
                'success'       => false,
                'description'   => 'Student Answer Sheet already exist!'
            ]);
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentAnswerSheet  $studentAnswerSheet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ret = $this->response_message();

        $studentAnswerSheet = StudentAnswerSheet::select(
            'student_answer_sheets.*',
            'answer_key_name',
            'school_year_from',
            'school_year_to',
            'section',
            'subject',
            'semester'
        )
            ->with([
                'student_answer_sheet_info' => function ($query) {
                    $query->select("student_answer_sheet_infos.*")
                        ->whereNull('students.deleted_at')
                        ->orderBy('student_answer_sheet_infos.id', 'asc')
                        ->studentJoin();
                },
                'student_answer_sheet_info.student',
                'answer_key',
                'answer_key.answer_key_info',
                'teacher'
            ])
            ->answerKey()
            ->schoolYear()
            ->section()
            ->subject()
            ->find($id);

        if ($studentAnswerSheet) {
            $ret = $this->response_message([
                'success'       => true,
                'description'   => 'Data found!',
                'data'          => $studentAnswerSheet
            ]);
        }

        return response()->json($ret, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentAnswerSheet  $studentAnswerSheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ret = $this->response_message();

        $data = $request->validate([
            'answer_key_id'     => 'required',
            'school_year_id'    => 'required',
            'section_id'        => 'required',
            'subject_id'        => 'required',
        ]);


        $studentAnswerSheets = StudentAnswerSheet::find($id);

        if ($studentAnswerSheets) {
            $studentAnswerSheets->fill($data);

            if ($studentAnswerSheets->save()) {
                if ($request->info) {
                    foreach ($request->info as $key => $value) {

                        $info_code = StudentAnswerSheetInfo::where('student_answer_sheet_id', $studentAnswerSheets->id)
                            ->orderBy('id', 'desc')
                            ->first();

                        $last_info_code = 1;

                        if ($info_code) {
                            $last_info_code = explode("-", $info_code->code)[1] + 1;
                        }

                        $exist = StudentAnswerSheetInfo::where('student_id', $value['student_enrollee_id'])
                            ->where('student_answer_sheet_id', $studentAnswerSheets->id)
                            ->first();

                        if (!$exist) {
                            $studentAnswerSheets->student_answer_sheet_info()->create([
                                'student_id'    => $value['student_enrollee_id'],
                                'code'          => $this->generate_number(6, sprintf("%04d", $last_info_code))
                            ]);
                        }
                    }
                }
            }
        }

        $ret = $this->response_message([
            'success'       => true,
            'description'   => 'Data inserted successfully!',
            'id'            => $studentAnswerSheets->id
        ]);

        return response()->json($ret, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentAnswerSheet  $studentAnswerSheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $ret = $this->response_message();

        $studentAnswerSheets = StudentAnswerSheet::find($id);

        if ($studentAnswerSheets) {
            if ($studentAnswerSheets->delete()) {
                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data deleted successfully!'
                ]);
            }
        }

        return response()->json($ret, 200);
    }
}
