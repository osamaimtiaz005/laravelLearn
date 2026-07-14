<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class ElqQueryBuilder extends Controller
{
    //
    public function studentList()
    {
        // all() already returns a list (Collection) — ready for @forelse
        $data = Student::all();
        return view('elqQueryBuilder.studentList', ['data' => $data]);
    }

    public function studentById($id)
    {
        // find($id) = Eloquent model method → returns ONE Student OR null (not a list)
        $student = Student::find($id);

        // Make self into an array so @forelse($data as $student) can loop:
        //   found  → [ $student ]   (1 item list)
        //   null   → []             (empty list → @empty branch)
        $data = $student ? [$student] : [];

        return view('elqQueryBuilder.studentList', ['data' => $data]);
    }
    public function studentByName($name)
    {
        // where()->get() already returns a Collection (list of Student models).
        // Do NOT wrap it again as [$student] — that nests the Collection inside an array,
        // so @forelse's $student becomes a Collection and $student->id crashes.
        $data = Student::where('name', $name)->get();

        return view('elqQueryBuilder.studentList', ['data' => $data]);
    }

    public function studentByBatch($batch)
    {
        // where()->get() already returns a list
        $data = Student::where('batch', $batch)->get();
        return view('elqQueryBuilder.studentList', ['data' => $data]);
    }

    public function studentByEmail($email)
    {
        $data = Student::where('email', $email)->get();
        return view('elqQueryBuilder.studentList', ['data' => $data]);
    }

    public function studentByPhone($phone)
    {
        $data = Student::where('phone', $phone)->get();
        return view('elqQueryBuilder.studentList', ['data' => $data]);
    }

    public function addStudent(Request $request)
    {
        // create() = Eloquent model method (needs $fillable on Student)
        //fillable is a property of the Student model that specifies which fields can be mass assigned.
        //mass assignment is a feature that allows you to assign values to multiple properties at once.

        Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'batch' => $request->batch,
        ]);
        return redirect()->route('elqQueryBuilder.studentList')->with('success', 'Student added successfully');
    }

    public function updateStudent(Request $request, $id)
    {
        // find() then call update() ON the model instance (Eloquent model methods)
        $student = Student::find($id);

        if ($student) {
            $student->update([
                'name' => $request->name,
                'email' => $request->email,
                'batch' => $request->batch,
            ]);
        }
        else{
            return redirect()->route('elqQueryBuilder.studentList')->with('error', 'Student not found');
        }

        return redirect()->route('elqQueryBuilder.studentList')->with('success', 'Student updated successfully');
    }

    public function deleteStudent($id)
    {
        // find() = one Student model (or null)
        // delete() = Eloquent instance method, no arguments → removes that row
        $student = Student::find($id);

        if ($student) {
            $student->delete();
        }
        else{
            return redirect()->route('elqQueryBuilder.studentList')->with('error', 'Student not found');
        }

        return redirect()->route('elqQueryBuilder.studentList')->with('success', 'Student deleted successfully');
    }

    public function searchStudent(Request $request)
    {
        // GET form sends: /searchStudent?keyword=ali
        // so we read it from the Request, not from a URL {keyword} segment
        //request is a class that represents the incoming HTTP request.
        //keyword is the name of the input field in the form.
        //request->keyword is the value of the input field in the form.
        // where() filters records by column value.
        // General syntax:
        // where(column, operator, value)
        // operator is a method that filters the results of the query.
        // value is the value of the input field in the form.
        // % is a wildcard that matches any number of characters.
        // % . $keyword . % is a wildcard that matches any number of characters before and after the keyword.
        // get is a method that returns the results of the query.

        $keyword = $request->keyword;

        $data = Student::where('name', 'like', '%' . $keyword . '%')
            ->orWhere('email', 'like', '%' . $keyword . '%')
            ->orWhere('batch', 'like', '%' . $keyword . '%')
            ->get();

        return view('elqQueryBuilder.studentList', ['data' => $data]);
    }
}
