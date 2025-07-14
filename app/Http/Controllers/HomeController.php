<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Enums\FormStatus;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = Form::where('status', FormStatus::ACTIVE->value)->select('label', 'status', 'id')->orderBy('id','DESC')->get();
        return view('welcome', compact('forms'));
    }
}