<?php

use App\Http\Controllers\Controller;
use App\User;
use \Illuminate\Http\Request;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::get();

        return view('users.index')->with(compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $user = new User;

        return view('users.create')->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $request = Request::capture();
        $request->merge(['password' => Hash::make($request->password)]);

        $user = User::create($request->all());

        return redirect('/admin/users')->with($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('users.edit')->with(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $form = $request->all();

        // if password is empty then leave it alone
        if (empty($form['password'])) {
            array_forget($form, 'password');
        } else {
            $form['password'] = Hash::make($form['password']);
        }

        $user = User::findOrFail($id);

        $user->update($form);

        return redirect('/admin/users');
    }
}
