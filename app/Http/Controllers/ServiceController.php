<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

     public function __construct()
    {
       $this->middleware('auth');
    }


	  public function index()
    {

        return view("services.index");
    }


  public function edit($id)
  {
      return view('services.edit')->with('id',$id);
  }

   public function create()
  {
      return view('services.create');
  }


}
