<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Institution;
use App\Models\LriMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LriController extends Controller
{
    public function getById($id){
        
        session(['lri_id' => $id]);
        $lri_id = session('lri_id');
        $lri_member = Employee::where('institution_id', $lri_id)
                        ->orderBy('order', 'asc')
                        ->get();
        $institution = Institution::where('id', $id)->first(); 
        
        return view('lri.index', compact('institution', 'lri_member'));
    }
    public function getAnggotaLri($id){
        
        $lri_id = session('lri_id');
        $lri_member = Employee::where('institution_id', $lri_id)
                        ->orderBy('order', 'asc')
                        ->get();
        $institution = Institution::where('id', $id)->first(); 
        
        return view('lri.anggota', compact('institution', 'lri_member'));
    }
    public function getTugasPokok($id){
        
        $lri_id = session('lri_id');
        $institution = Institution::where('id', $id)->first(); 
        
        return view('lri.tupoksi', compact('institution'));
    }
}
