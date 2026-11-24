<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class PublicController { public function __invoke(){ return view('public.home',['schedules'=>DB::table('schedules')->where('is_published',true)->orderBy('date')->get()]); } }
