<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->page_title = "Support";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $supports = Support::select('supports.*', 'firstname', 'lastname')->where(function ($query) use ($request) {
            if ($request->search) {
                $query->orWhere('ticket', 'LIKE', "%$request->search%");
                $query->orWhere('title', 'LIKE', "%$request->search%");
                $query->orWhere('complain', 'LIKE', "%$request->search%");
                $query->orWhere('title', 'LIKE', "%$request->search%");
                $query->orWhere('supports.status', 'LIKE', "%$request->search%");
                $query->orWhere('supports.created_at', 'LIKE', "%$request->search%");
            }
        })
            ->user();

        if (auth()->user()->user_role_id != 1) {
            $supports->where('user_id', auth()->user()->id);
        } else {
            if ($request->user_id) {
                $supports->where('user_id', $request->user_id);
            }
        }

        $table_columns = new Support();
        $table_columns = $table_columns->getTableColumns();

        if ($request->sort_order != '' && in_array($request->sort_field, $table_columns)) {
            $supports->orderBy($request->sort_field, $request->sort_order == 'ascend' ? 'asc' : 'desc');
        }

        if ($request->page) {
            $supports = $supports->paginate(10);
        } else {
            $supports = $supports->orderBy('ticket', 'asc')->get();
        }

        $ret = [
            'success'   => true,
            'data'      => $supports
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
            'title' => 'required',
            'complain' => 'required'
        ]);

        $code = Support::where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->first();

        $last_code = 1;

        if ($code) {
            $last_code = explode("-", $code->ticket)[2] + 1;
        }

        $data += [
            'user_id' => auth()->user()->id,
            'ticket' => 'TKT-' . $this->generate_number(6, sprintf("%04d", $last_code))
        ];

        $supportExist = Support::where('title', $request->title)
            ->where('user_id', auth()->user()->id)
            ->whereNull('deleted_at')
            ->count();

        if ($supportExist == 0) {
            $supports = Support::create($data);

            if ($supports) {
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
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ret = $this->response_message();

        $supports = Support::with([
            'support_conversation',
            'support_conversation.user_from',
            'support_conversation.user_to'
        ])->find($id);

        if ($supports) {
            $ret = $this->response_message([
                'success'       => true,
                'description'   => 'Data found!',
                'data'          => $supports
            ]);
        }

        return response()->json($ret, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Support $support)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function destroy(Support $support)
    {
        //
    }
}