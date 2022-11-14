<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Support;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->action == 'user_students_yearly') {
            return response()->json($this->userStudentsYearLy($request), 200);
        } else if ($request->action == 'admin_users') {
            return response()->json($this->adminUsers($request), 200);
        }
    }

    private function userStudentsYearLy()
    {
        $school_years = SchoolYear::with(['students' => function ($query) {
            $query->whereNull('deleted_at');
        }])
            ->where('created_by', auth()->user()->id)
            ->get();
        return $school_years;
    }

    private function adminUsers()
    {
        $users = User::where('user_role_id', '!=', 1)->get();
        $supportPending = Support::where('status', 0)->count();
        $supportProcess = Support::where('status', 1)->count();
        $supportFinish = Support::where('status', 2)->count();

        return [
            "users"             => $users,
            "supportPending"    => $supportPending,
            "supportProcess"    => $supportProcess,
            "supportFinish"     => $supportFinish
        ];
    }
}