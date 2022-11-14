<?php

namespace App\Http\Controllers;

use App\Models\Support;
use App\Models\SupportConversation;
use Illuminate\Http\Request;

class SupportConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'chat' => 'required',
        ]);

        $data += [
            'support_id'    => $request->support_id,
            'user_from'     => auth()->user()->id,
            'user_to'       => $request->user_to
        ];

        $createdChat = SupportConversation::create($data);

        if ($createdChat) {
            $countcreatedChat = SupportConversation::where('support_id', $request->support_id)->count();

            if ($countcreatedChat == 1) {
                $supoortdd = Support::find($request->support_id);
                if ($supoortdd) {
                    $supoortdd->fill(['status' => 1]);
                    $supoortdd->save();
                }
            }

            if ($request->chat == 'done') {
                $supoortdd = Support::find($request->support_id);
                if ($supoortdd) {
                    $supoortdd->fill(['status' => 2]);
                    $supoortdd->save();
                }
            }

            $ret = $this->response_message([
                'success'       => true,
                'description'   => 'Chat inserted successfully!',
            ]);
        }

        return response()->json($ret, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportConversation  $supportConversation
     * @return \Illuminate\Http\Response
     */
    public function show(SupportConversation $supportConversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportConversation  $supportConversation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupportConversation $supportConversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupportConversation  $supportConversation
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupportConversation $supportConversation)
    {
        //
    }
}