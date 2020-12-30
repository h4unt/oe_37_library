<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\RepositoryInterface\UserRepositoryInterface;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('searchName');
        $request->session()->forget('searchRole');
        $fullname = $request->input('searchName');
        $role = $request->input('searchRole');
        $users = $this->user->getQuerySearch($fullname, $role);
        $request->session()->put('searchName', $fullname);
        $request->session()->put('searchRole', $role);
        
        return view('user.users', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->user->find($id);
        if(!$user) return redirect()->route('admin.users.index')->with('message', ['msg' => trans('admin.user_message.not_found'), 'status' => 'danger']);
        
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        $user = $this->user->update($user->user_id, $request->all());
        if(!$user) return redirect()->route('admin.users.index')->with('message', ['msg' => trans('admin.user_message.not_found'), 'status' => 'danger']);

        return redirect()->route('admin.users.index')->with('message', ['msg' => trans('admin.user_message.edit_success'), 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->user->delete($id);
        if(!$user) return redirect()->route('admin.users.index')->with('message', ['msg' => trans('admin.user_message.not_found'), 'status' => 'danger']);

        return redirect()->route('admin.users.index')->with('message', ['msg' => trans('admin.user_message.del_success'), 'status' => 'success']);
    }

    public function export(Request $request)
    {
        if ($request->session()->has('searchName') || $request->session()->has('searchRole')) {
            $nameValue = $request->session()->get('searchName');
            $roleValue = $request->session()->get('searchRole');

            return (new UserExport($nameValue, $roleValue))
                ->download(Config::get('app.exportUser'));
        }
        return redirect()->back();
    }
    public function readNoti()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    }

    public function myAccount()
    {
        return view('user.my-account');
    }
}
