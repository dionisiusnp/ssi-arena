<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index()
    {
        return view('member.daftar.index');
    }

    public function register(CreateUserRequest $request)
    {
        $this->userService->store($request->toArray());
        return redirect('/')->with('success','Akun pemain telah berhasil didaftarkan.');
    }
}
