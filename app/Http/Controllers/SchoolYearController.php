<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->page_title = "School Year";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $school_years = SchoolYear::where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere('school_year_from', 'LIKE', "%$request->search%");
                $query->orWhere('school_year_to', 'LIKE', "%$request->search%");
                $query->orWhere('status', 'LIKE', "%$request->search%");
            }
        });

        if (auth()->user()->user_role_id != 1) {
            $school_years->where('created_by', auth()->user()->id);
        }

        $table_columns = new SchoolYear();
        $table_columns = $table_columns->getTableColumns();

        if ($request->sort_order != '' && in_array($request->sort_field, $table_columns)) {
            $school_years->orderBy($request->sort_field, $request->sort_order == 'ascend' ? 'asc' : 'desc');
        }

        if ($request->page) {
            $school_years = $school_years->paginate(10);
        } else {
            $school_years = $school_years->orderBy('school_year_from', 'asc')->get();
        }

        $ret = [
            'success'   => true,
            'data'      => $school_years
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
            'school_year_from'  => 'required',
            'school_year_to'    => 'required',
            'status'            => 'required'
        ]);

        $school_year_exits = SchoolYear::where('school_year_from', '=', $request->school_year_from)
            ->where('school_year_to', '=', $request->school_year_to)
            ->where('created_by', auth()->user()->id)
            ->whereNull('deleted_at')
            ->count();

        if ($school_year_exits == 0) {
            if ($request->status == 1) {
                SchoolYear::where('status', 1)
                    ->where('created_by', auth()->user()->id)
                    ->update(['status' => 0]);
            }

            $school_years = SchoolYear::create($data);

            if ($school_years) {
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
     * @param  \App\Models\SchoolYear  $schoolYear
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolYear $schoolYear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SchoolYear  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ret = $this->response_message();

        $school_years = SchoolYear::find($id);

        if ($school_years) {
            $error = false;

            if (!$request->stat) {
                $school_year_exits = SchoolYear::where('school_year_from', '=', $request->school_year_from)
                    ->where('school_year_to', '=', $request->school_year_to)
                    ->where('created_by', auth()->user()->id)
                    ->whereNull('deleted_at')
                    ->count();

                if ($school_years->school_year_from != $request->school_year_from || $school_years->school_year_to != $request->school_year_to) {
                    if ($school_year_exits == 0) {
                        $request->validate([
                            'school_year_from'  => 'required',
                            'school_year_to'    => 'required',
                            'status'            => 'required'
                        ]);
                    } else {
                        $error = true;

                        $ret = $this->response_message([
                            'success'       => false,
                            'description'   => 'Data already exist!'
                        ]);
                    }
                }
            } else {
                $request->validate([
                    'status' => 'required'
                ]);
            }

            if ($error == false) {
                if ($request->status == 1) {
                    SchoolYear::where('status', 1)
                        ->where('created_by', auth()->user()->id)
                        ->update(['status' => 0]);
                }

                $school_years->fill($request->except(['stat']));

                if ($school_years->save()) {
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
     * @param  \App\Models\SchoolYear  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $ret = $this->response_message();

        $school_years = SchoolYear::find($id);

        if ($school_years) {
            if ($school_years->delete()) {
                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data deleted successfully!'
                ]);
            }
        }

        return response()->json($ret, 200);
    }
}
