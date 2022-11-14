<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->page_title = "User";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::select(
            'users.id',
            'users.email',
            'users.firstname',
            'users.middlename',
            'users.lastname',
            'users.name_ext',
            'users.gender',
            'users.birthdate',
            'users.contact_no',
            'users.user_role_id',
            'users.status',
            'user_role'
        )
            ->where(function ($query) use ($request) {
                if ($request->search) {
                    $query->orWhere('firstname', 'LIKE', "%$request->search%")
                        ->orWhere('middlename', 'LIKE', "%$request->search%")
                        ->orWhere('lastname', 'LIKE', "%$request->search%")
                        ->orWhere('name_ext', 'LIKE', "%$request->search%")
                        ->orWhere('email', 'LIKE', "%$request->search%")
                        ->orWhere('gender', 'LIKE', "%$request->search%")
                        ->orWhere('birthdate', 'LIKE', "%$request->search%")
                        ->orWhere('contact_no', 'LIKE', "%$request->search%")
                        ->orWhere('user_role', 'LIKE', "%$request->search%")
                        ->orWhere('status', 'LIKE', "%$request->search%");
                }
            })
            ->with(['user_image' => function ($query) {
                $query->orderBy('id', 'desc');
            }])
            ->userRole();

        $table_columns = new User();
        $table_columns = $table_columns->getTableColumns();

        if ($request->sort_order != '' && in_array($request->sort_field, $table_columns)) {
            $users->orderBy($request->sort_field, $request->sort_order == 'ascend' ? 'asc' : 'desc');
        }

        if ($request->page) {
            $users = $users->paginate(10);
        } else {
            $users = $users->orderBy('lastname', 'asc')->get();
        }

        $ret = [
            'success'   => true,
            'data'      => $users
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

        if ($request->from == "update_user") {
            $ret = $this->userUpdateProfile($request);
        } else {
            if ($request->id != '') {
                $ret = $this->userUpdate($request);
            } else {
                $ret = $this->userCreate($request);
            }
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ret = $this->response_message();

        $users = User::select(
            'id',
            'email',
            'firstname',
            'middlename',
            'lastname',
            'name_ext',
            'gender',
            'birthdate',
            'contact_no',
            'user_role_id',
            'status',
        )->with(['user_image' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->find($id);

        if ($users) {

            $ret = $this->response_message([
                'success'       => true,
                'description'   => 'Data found!',
                'data'          => $users
            ]);
        }

        return response()->json($ret, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ret = $this->response_message();

        $user = User::find($id);

        if ($user && $user->delete()) {
            $ret = $this->response_message([
                'success'       => true,
                'description'   => 'Data deleted successfully!'
            ]);
        }

        return response()->json($ret, 200);
    }

    private function  userCreate($request)
    {
        $ret = $this->response_message();

        $data = $request->validate([
            'firstname'     => 'required',
            'middlename'    => '',
            'lastname'      => 'required',
            'name_ext'      => 'max:5',
            'email'         => ['required', 'unique:users'],
            'password'      => 'required',
            'gender'        => 'required',
            'birthdate'     => '',
            'contact_no'    => '',
            'user_role_id'  => 'required',
            'status'        => 'required'
        ]);

        $users = User::create($data);
        $users->password = Hash::make($request->password);

        if ($users->save()) {
            if ($request->hasFile('user_image')) {
                $userImageFile = $request->file('user_image');
                $userImageFileName = $userImageFile->getClientOriginalName();
                $userImageFileImage = time() . '_' . $userImageFileName;
                $userImageFileSize = $this->formatSizeUnits($userImageFile->getSize());
                // $userImageFileImage = $userImageFile->storeAs('uploads/user_images', $userImageFileImage, 'public');
                $userImageFile->move(public_path('uploads/user_images'), $userImageFileImage);

                $users->user_image()->create([
                    'file_path' => 'uploads/user_images/' . $userImageFileImage,
                    'file_name' => $userImageFileName,
                    'file_size' => $userImageFileSize,
                ]);
            }

            $ret = $this->response_message([
                'success'       => true,
                'description'   => 'Data inserted successfully!',
                'action'        => 1
            ]);
        }

        return $ret;
    }

    private function userUpdate($request)
    {
        $ret = $this->response_message();

        $users = User::find($request->id);

        if ($users) {
            if ($request->isStat != 1) {
                $request->validate([
                    'firstname'     => 'required',
                    'middlename'    => '',
                    'lastname'      => 'required',
                    'name_ext'      => '',
                    'email'         => 'required',
                    'gender'        => 'required',
                    'birthdate'     => '',
                    'contact_no'    => '',
                    'user_role_id'  => 'required',
                    'status'        => 'required'
                ]);

                if ($users->email != $request->email) {
                    $request->validate([
                        'email'  => ['required', 'unique:users'],
                    ]);
                }
            } else {
                if (empty($request->status)) {
                    $request->validate([
                        'status'  => 'required',
                    ]);
                }
            }

            $updated = $users->fill($request->all())->save();

            if ($request->password != '') {
                $users->password = Hash::make($request->password);
                $users->save();
            }

            if ($updated) {
                if ($request->hasFile('user_image')) {
                    $userImageFile = $request->file('user_image');
                    $userImageFileName = $userImageFile->getClientOriginalName();
                    $userImageFileImage = time() . '_' . $userImageFileName;
                    $userImageFileSize = $this->formatSizeUnits($userImageFile->getSize());
                    // $userImageFileImage = $userImageFile->storeAs('uploads/user_images', $userImageFileImage, 'public');
                    $userImageFile->move(public_path('uploads/user_images'), $userImageFileImage);

                    $users->user_image()->create([
                        'file_path' => 'uploads/user_images/' . $userImageFileImage,
                        'file_name' => $userImageFileName,
                        'file_size' => $userImageFileSize,
                    ]);
                }

                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data updated successfully!',
                    'action'        => 2
                ]);
            }
        }

        return $ret;
    }

    private function userUpdateProfile($request)
    {
        $ret = $this->response_message();

        $data = $request->validate([
            'firstname'     => 'required',
            'middlename'    => '',
            'lastname'      => 'required',
            'name_ext'      => '',
            'email'         => 'required',
            'gender'        => 'required',
            'birthdate'     => '',
            'contact_no'    => '',
        ]);


        if ($request->password != '') {
            $data += ['password' => Hash::make($request->password)];
        }

        $users = User::find(auth()->user()->id);

        if ($users) {
            $updated = auth()->user()->update($data);

            if ($updated) {
                if ($request->hasFile('user_image')) {
                    $userImageFile = $request->file('user_image');
                    $userImageFileName = $userImageFile->getClientOriginalName();
                    $userImageFileImage = time() . '_' . $userImageFileName;
                    $userImageFileSize = $this->formatSizeUnits($userImageFile->getSize());
                    // $userImageFileImage = $userImageFile->storeAs('uploads/user_images', $userImageFileImage, 'public');
                    $userImageFile->move(public_path('uploads/user_images'), $userImageFileImage);

                    $users->user_image()->create([
                        'file_path' => 'uploads/user_images/' . $userImageFileImage,
                        'file_name' => $userImageFileName,
                        'file_size' => $userImageFileSize,
                    ]);
                }

                $ret = $this->response_message([
                    'success'       => true,
                    'description'   => 'Data updated successfully!'
                ]);
            }
        }


        return $ret;
    }
}