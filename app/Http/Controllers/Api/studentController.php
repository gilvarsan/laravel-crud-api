<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class studentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        if ($students->isEmpty()) {
            $data = [
                'message' => 'No se econtraron estudiantes',
                'status' => 200
            ];

            return response()->json($data, 200);
        }

        $data = [
            'students' => $students,
            'status' => 200
        ];

        return response()->json($data, 200);

        return 'Obteniendo lista de estudiantes desde el controller';
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:javascript,php,java'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language
        ]);

        if (!$student) {
            $data = [
                'message' => 'Error al crear el estudiante',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'student' => $student,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Estudiante no ecnontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $student->delete();

        $data = [
            'message' => 'Estudiante Eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Estudiante no ecnontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:javascript,php,java'
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error al validar los datos',
                'errors' => $validator->errors(),
                'status' => 400,

            ];
            return response()->json($data, 400);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;
        $student->save();

        $data = [
            'message' => 'Estudiante actualizado',
            'student' => $student,
            'status' => 200,
        ];
        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Estudiante no ecnontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'email' => 'email|unique:student',
            'phone' => 'digits:10',
            'language' => 'in:javascript,php,java'
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error al validar los datos',
                'errors' => $validator->errors(),
                'status' => 400,

            ];
            return response()->json($data, 400);
        }

        if ($request->has('name')) {
            $student->name = $request->name;
        }
        if ($request->has('email')) {
            $student->email = $request->email;
        }
        if ($request->has('phone')) {
            $student->phone = $request->phone;
        }
        if ($request->has('language')) {
            $student->language = $request->language;
        }
        $student->save();
        $data = [
            'message' => 'Estudiante actualizado',
            'student' => $student,
            'status' => 200,
        ];
        return response()->json($data, 200);
    }
}