<?php

namespace App\Http\Controllers;

use App\Models\AnswerKey;
use Illuminate\Http\Request;

class AnswerKeyController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->page_title = "Answer Key";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $answerKeys = AnswerKey::select('answer_keys.*', 'school_year_from', 'school_year_to')
            ->with('answer_key_info')
            ->where(function ($query) use ($request) {
                if ($request->search) {
                    $query->where('code', 'LIKE', "%$request->search%");
                    $query->where('answer_key_name', 'LIKE', "%$request->search%");
                    $query->where('school_year_from', 'LIKE', "%$request->search%");
                    $query->where('school_year_to', 'LIKE', "%$request->search%");
                    $query->where('semester', 'LIKE', "%$request->search%");
                    $query->where('choices_type', 'LIKE', "%$request->search%");
                    $query->where('number_of_question', 'LIKE', "%$request->search%");
                }
            })
            ->schoolYear();

        if (auth()->user()->user_role_id != 1) {
            $answerKeys->where('answer_keys.created_by', auth()->user()->id);
        }

        $table_columns = new AnswerKey();
        $table_columns = $table_columns->getTableColumns();

        if ($request->sort_order != '' && in_array($request->sort_field, $table_columns)) {
            $answerKeys->orderBy($request->sort_field, $request->sort_order == 'ascend' ? 'asc' : 'desc');
        }

        if ($request->page) {
            $answerKeys = $answerKeys->paginate(10);
        } else {
            $answerKeys = $answerKeys->orderBy('answer_keys.created_at', 'asc')->get();
        }

        $ret = [
            'success'   => true,
            'data'      => $answerKeys
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
            'answer_key_name'       => 'required',
            'school_year_id'        => 'required',
            'choices_type'          => 'required',
            'number_of_question'    => 'required',
            'semester'              => 'required'
        ]);

        $code = AnswerKey::where('created_by', auth()->user()->id)
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->first();

        $last_code = 1;

        if ($code) {
            $last_code = explode("-", $code->code)[1] + 1;
        }

        $data += ['code' => $this->generate_number(6, sprintf("%04d", $last_code))];

        $exist = AnswerKey::where('created_by', auth()->user()->id)
            ->where('answer_key_name', $request->answer_key_name)
            ->where('school_year_id', $request->school_year_id)
            ->whereNull('deleted_at')
            ->count();

        if ($exist == 0) {
            $answer_keys = AnswerKey::create($data);

            if ($answer_keys) {
                if ($request->answer_key_info) {
                    foreach ($request->answer_key_info as $value) {
                        $answer_keys->answer_key_info()->create(['answer' => $value['answer']]);
                    }
                }

                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data inserted successfully!'
                ]);
            }
        } else {
            $ret = $this->response_message([
                'success'       => false,
                'description'   => 'Answer Key Name already exist!'
            ]);
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AnswerKey  $answerKey
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ret = $this->response_message();

        $answerKeys = AnswerKey::select('answer_keys.*', 'school_year_from', 'school_year_to')
            ->with(['answer_key_info' => function ($query) {
                $query->orderBy('id', 'asc');
            }, 'user'])
            ->schoolYear()
            ->where('answer_keys.id', $id)
            ->where('answer_keys.created_by', auth()->user()->id)
            ->first();

        if ($answerKeys) {
            $ret = $this->response_message([
                'success'       => true,
                'description'   => 'Data found!',
                'data'          => $answerKeys
            ]);
        }

        return response()->json($ret, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AnswerKey  $answerKey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ret = $this->response_message();

        $data = $request->validate([
            'answer_key_name'       => 'required',
            'school_year_id'        => 'required',
            'choices_type'          => 'required',
            'number_of_question'    => 'required',
            'semester'              => 'required'
        ]);

        $answerKeys = AnswerKey::find($id);

        if ($answerKeys) {
            if ($answerKeys->answer_key_name == $request->answer_key_name) {
                $answer_keys = $answerKeys->fill($data);

                if ($answer_keys->save()) {
                    if ($request->answer_key_info) {
                        $answer_keys->answer_key_info()->where('answer_key_id', $answer_keys->id)->delete();

                        foreach ($request->answer_key_info as $value) {
                            $answer_keys->answer_key_info()
                                ->create([
                                    'answer' => $value['answer']
                                ]);
                        }
                    }

                    $ret = $this->response_message([
                        'success'       => true,
                        'description'   => 'Data updated successfully!'
                    ]);
                }
            } else {
                $exist = AnswerKey::where('created_by', auth()->user()->id)
                    ->where('answer_key_name', $request->answer_key_name)
                    ->whereNull('deleted_at')
                    ->count();

                if ($exist == 0) {
                    $answer_keys = $answerKeys->fill($data);

                    if ($answer_keys->save()) {
                        if ($request->answer_key_info) {
                            $answer_keys->answer_key_info()->where('answer_key_id', $answer_keys->id)->delete();

                            foreach ($request->answer_key_info as $value) {
                                $answer_keys->answer_key_info()
                                    ->create([
                                        'answer' => $value['answer']
                                    ]);
                            }
                        }

                        $ret = $this->response_message([
                            'success'       => true,
                            'description'   => 'Data updated successfully!'
                        ]);
                    }
                } else {
                    $ret = $this->response_message([
                        'success'       => false,
                        'description'   => 'Answer Key Name already exist!'
                    ]);
                }
            }
        }

        return response()->json($ret, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AnswerKey  $answerKey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $ret = $this->response_message();

        $answerKeys = AnswerKey::find($id);

        if ($answerKeys) {
            if ($answerKeys->delete()) {
                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data deleted successfully!'
                ]);
            }
        }

        return response()->json($ret, 200);
    }
}
