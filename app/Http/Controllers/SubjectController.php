<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->page_title = "Subject";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subjects = Subject::where(function ($query) use ($request) {
            if ($request->search) {
                $query->where('subject', 'LIKE', "%$request->search%");
            }
        });

        if (auth()->user()->user_role_id != 1) {
            $subjects->where('created_by', auth()->user()->id);
        }

        $table_columns = new Subject();
        $table_columns = $table_columns->getTableColumns();

        if ($request->sort_order != '' && in_array($request->sort_field, $table_columns)) {
            $subjects->orderBy($request->sort_field, $request->sort_order == 'ascend' ? 'asc' : 'desc');
        }

        if ($request->page) {
            $subjects = $subjects->paginate(10);
        } else {
            $subjects = $subjects->orderBy('subject', 'asc')->get();
        }

        $ret = [
            'success'   => true,
            'data'      => $subjects
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
            'subject' => 'required'
        ]);

        $subjectsExist = Subject::where('subject', $request->subject)
            ->where('created_by', auth()->user()->id)
            ->whereNull('deleted_at')
            ->count();

        if ($subjectsExist == 0) {
            $subjects = Subject::create($data);
            if ($subjects) {

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
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ret = $this->response_message();

        $subject = Subject::find($id);

        if ($subject) {
            $error = false;

            if ($subject->subject != $request->subject) {
                $subjectsExist = Subject::where('subject', $request->subject)
                    ->where('created_by', auth()->user()->id)
                    ->whereNull('deleted_at')
                    ->count();

                if ($subjectsExist == 0) {
                    $request->validate([
                        'subject' => ['required', 'unique:subjects']
                    ]);
                } else {
                    $error = true;

                    $ret = $this->response_message([
                        'success'       => false,
                        'description'   => 'Data already exist!'
                    ]);
                }
            }

            if ($error == false) {
                $subject->fill($request->all());

                if ($subject->save()) {
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
     * @param  \App\Models\Subject  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $ret = $this->response_message();

        $subject = Subject::find($id);

        if ($subject) {
            if ($subject->delete()) {
                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data deleted successfully!'
                ]);
            }
        }

        return response()->json($ret, 200);
    }
}
