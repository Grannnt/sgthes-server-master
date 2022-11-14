<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->page_title = "Section";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sections = Section::where(function ($query) use ($request) {
            if ($request->search) {
                $query->where('section', 'LIKE', "%$request->search%");
            }
        });

        if (auth()->user()->user_role_id != 1) {
            $sections->where('created_by', auth()->user()->id);
        }

        $table_columns = new Section();
        $table_columns = $table_columns->getTableColumns();

        if ($request->sort_order != '' && in_array($request->sort_field, $table_columns)) {
            $sections->orderBy($request->sort_field, $request->sort_order == 'ascend' ? 'asc' : 'desc');
        }

        if ($request->page) {
            $sections = $sections->paginate(10);
        } else {
            $sections = $sections->orderBy('section', 'asc')->get();
        }

        $ret = [
            'success'   => true,
            'data'      => $sections
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
            'section' => 'required'
        ]);

        $sectionExist = Section::where('section', $request->section)
            ->where('created_by', auth()->user()->id)
            ->whereNull('deleted_at')
            ->count();

        if ($sectionExist == 0) {
            $section = Section::create($data);

            if ($section) {
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
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ret = $this->response_message();

        $section = Section::find($id);

        if ($section) {
            $error = false;

            if ($section->section != $request->section) {
                $sectionExist = Section::where('section', $request->section)
                    ->where('created_by', auth()->user()->id)
                    ->whereNull('deleted_at')
                    ->count();

                if ($sectionExist == 0) {
                    $request->validate([
                        'section' => 'required'
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
                $section->fill($request->all());

                if ($section->save()) {
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
     * @param  \App\Models\Section  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $ret = $this->response_message();

        $section = Section::find($id);

        if ($section) {
            if ($section->delete()) {
                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data deleted successfully!'
                ]);
            }
        }

        return response()->json($ret, 200);
    }
}
