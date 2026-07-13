<?php
namespace App\Http\Controllers;

class StudentController extends Controller
{
    public function show()
    {
        return "Student Show";
    }
    public function edit()
    {
        return "Student Edit";
    }
    public function delete()
    {
        return "Student Delete";
    }
    public function create()
    {
        return "Student Create";
    }
    public function about($name)
    {
        return "Student About " . $name;
    }
   
}
?>