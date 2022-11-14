<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->page_title = "User Role";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_roles = UserRole::where(function ($query) use ($request) {
            if ($request->search) {
                $query->where('user_role', 'LIKE', "%$request->search%");
            }
        });

        $table_columns = new UserRole();
        $table_columns = $table_columns->getTableColumns();

        if ($request->sort_order != '' && in_array($request->sort_field, $table_columns)) {
            $user_roles->orderBy($request->sort_field, $request->sort_order == 'ascend' ? 'asc' : 'desc');
        }

        if ($request->page) {
            $user_roles = $user_roles->paginate(10);
        } else {
            $user_roles = $user_roles->orderBy('user_role', 'asc')->get();
        }

        $ret = [
            'success'   => true,
            'data'      => $user_roles
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
            'user_role' => ['required', 'unique:user_roles']
        ]);

        $user_role = UserRole::create($data);

        if ($user_role) {

            $ret = $this->response_message([
                'success'       => true,
                'description'   => 'Data inserted successfully!'
            ]);
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function show(UserRole $userRole)
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

        $user_role = UserRole::find($id);

        if ($user_role) {
            if ($user_role->user_role != $request->user_role) {
                $request->validate([
                    'user_role' => ['required', 'unique:user_roles']
                ]);
            }

            $user_role->fill($request->all());

            if ($user_role->save()) {
                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data updated successfully!'
                ]);
            }
        }

        return response()->json($ret, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserRole  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $ret = $this->response_message();

        $user_role = UserRole::find($id);

        if ($user_role) {
            if ($user_role->delete()) {
                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data deleted successfully!'
                ]);
            }
        }

        return response()->json($ret, 200);
    }
}