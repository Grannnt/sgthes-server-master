<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->page_title = "Student Registered";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $students = Student::select(
            'students.id',
            'lrn',
            'access_code',
            'name',
            'sex',
            'birthdate',
            'contact_no',
            'section',
            'section_id',
            'school_year_from',
            'school_year_to',
            'school_year_id',
            'students.status'
        )
            ->where(function ($query) use ($request) {
                if ($request->search) {
                    $query->orWhere('lrn', 'LIKE', "%$request->search%");
                    $query->orWhere('access_code', 'LIKE', "%$request->search%");
                    $query->orWhere('name', 'LIKE', "%$request->search%");
                    $query->orWhere('sex', 'LIKE', "%$request->search%");
                    $query->orWhere('birthdate', 'LIKE', "%$request->search%");
                    $query->orWhere('contact_no', 'LIKE', "%$request->search%");
                    $query->orWhere('section', 'LIKE', "%$request->search%");
                    $query->orWhere('students.status', 'LIKE', "%$request->search%");
                }
            })
            ->schoolYear()
            ->section();

        if (auth()->user()->user_role_id != 1) {
            $students->where('students.created_by', auth()->user()->id);
        } else {
            if ($request->user_id) {
                $students->where('students.created_by', $request->user_id);
            }
        }

        if ($request->school_year_id) {
            $students->where('school_year_id', $request->school_year_id);
        }

        if ($request->section_id) {
            $students->where('section_id', $request->section_id);
        }

        $table_columns = new Student();
        $table_columns = $table_columns->getTableColumns();

        if ($request->sort_order != '' && in_array($request->sort_field, $table_columns)) {
            $students->orderBy($request->sort_field, $request->sort_order == 'ascend' ? 'asc' : 'desc');
        }

        if ($request->page) {
            $students = $students->paginate(10);
        } else {
            $students = $students->orderBy('name', 'asc')->get();
        }

        $ret = [
            'success'   => true,
            'data'      => $students
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

        $studentLrnExist = Student::where('lrn', $request->lrn)
            ->where('created_by', auth()->user()->id)
            ->whereNull('deleted_at')
            ->count();

        if ($studentLrnExist == 0) {
            $data = $request->validate([
                'name'              => 'required',
                'lrn'               => 'required',
                'sex'               => 'required',
                'birthdate'         => '',
                'contact_no'        => '',
                'school_year_id'    => 'required',
                'section_id'        => 'required',
                'status'            => '',
            ]);

            $data += ["access_code" => $this->generate_number(6)];

            $student = Student::create($data);

            if ($student) {
                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data inserted successfully!'
                ]);
            }
        } else {
            $ret = $this->response_message([
                'success'       => false,
                'description'   => 'LRN already exist!'
            ]);
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserRole  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ret = $this->response_message();

        $student = Student::find($id);

        if ($student) {
            $error = false;

            if ($student->name != $request->name || $student->lrn != $request->lrn) {
                $studentExist = Student::where('lrn', $request->lrn)
                    ->where('created_by', auth()->user()->id)
                    ->whereNull('deleted_at')
                    ->count();

                if ($studentExist == 0) {
                    $request->validate([
                        'name'              => 'required',
                        'lrn'               => 'required',
                        'sex'               => 'required',
                        'birthdate'         => '',
                        'contact_no'        => '',
                        'school_year_id'    => 'required',
                        'section_id'        => 'required',
                        'status'            => '',
                    ]);
                } else {
                    $error = true;
                    $ret = $this->response_message([
                        'success'       => false,
                        'description'   => 'LRN already exist!'
                    ]);
                }
            }

            if ($error == false) {
                $student->fill($request->except(['section', 'school_year_from', 'school_year_to']));

                if ($student->save()) {
                    $ret = $this->response_message([
                        'success'       => true,
                        'description'   => 'Data updated successfully!'
                    ]);
                }
            }
        }

        return response()->json($ret, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = $this->response_message();

        $student = Student::find($id);

        if ($student) {
            if ($student->delete()) {
                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data deleted successfully!'
                ]);
            }
        }

        return response()->json($ret, 200);
    }
}
