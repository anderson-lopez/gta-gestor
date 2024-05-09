<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class employeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();

        if ($employees->isEmpty()) {
            $data  = [
                'message'=> 'No se encontraron Registros',
                'employees' => $employees,
                'status'=> 200
            ];
            return response()->json($data, 200); 
        }

        return response()->json($employees, 200);
    }

    public function store(Request $request)
    {
        //Reglas de validación
        $rules = [
            'first_last_name' => 'required|regex:/^[A-Z]+$/|max:20',
            'second_last_name' => 'required|regex:/^[A-Z]+$/|max:20',
            'first_name' => 'required|regex:/^[A-Z]+$/|max:20',
            'other_names' => 'nullable|regex:/^[A-Z\s]+$/|max:50',
            'country' => 'required|in:Colombia,United States',
            'id_type' => 'required',
            'id_number' => 'required|unique:employees,id_number|max:20',
            'email' => 'required|email|unique:employees,email|max:300',
            'entry_date' => 'required|date|before_or_equal:today|after_or_equal:' . now()->subMonth(),
            'area' => 'required',
            'status' => 'required|in:Active',
        ];
    
        // Mensajes personalizados para las reglas de validación
        $messages = [
            'first_last_name.required' => 'El primer apellido es obligatorio.',
            'first_last_name.regex' => 'El primer apellido solo permite caracteres de la A a la Z, mayúsculas, sin Acentuaciones',
            'first_last_name.max' => 'El primer apellido no puede tener más de 20 caracteres.',
            'second_last_name.required' => 'El segundo apellido es obligatorio.',
            'second_last_name.regex' => 'El segundo apellido solo permite caracteres de la A a la Z, mayúsculas, sin Acentuaciones',
            'second_last_name.max' => 'El segundo apellido no puede tener más de 20 caracteres.',
            'first_name.required' => 'El primer nombre es obligatorio.',
            'first_name.regex' => 'El primer nombre solo permite caracteres de la A a la Z, mayúsculas, sin Acentuaciones',
            'first_name.max' => 'El primer nombre no puede tener más de 20 caracteres.',
            'other_names.regex' => 'Los otros nombres solo permiten caracteres de la A a la Z, mayúsculas, y espacios, sin Acentuaciones',
            'other_names.max' => 'Los otros nombres no pueden tener más de 50 caracteres.',
            'country.required' => 'El país del empleo es obligatorio.',
            'country.in' => 'El país del empleo debe ser Colombia o Estados Unidos.',
            'id_type.required' => 'El tipo de identificación es obligatorio.',
            'id_type.regex' => 'El tipo de identificación solo permite caracteres de la A a la Z, sin Acentuaciones',
            'id_number.required' => 'El número de identificación es obligatorio.',
            'id_number.unique' => 'El número de identificación ya ha sido registrado.',
            'id_number.max' => 'El número de identificación no puede tener más de 20 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'El correo electrónico ya ha sido registrado.',
            'email.max' => 'El correo electrónico no puede tener más de 300 caracteres.',
            'entry_date.required' => 'La fecha de ingreso es obligatoria.',
            'entry_date.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'entry_date.before_or_equal' => 'La fecha de ingreso no puede ser superior a la fecha actual.',
            'entry_date.after_or_equal' => 'La fecha de ingreso no puede ser mayor a un mes anterior a la fecha actual.',
            'area.required' => 'El área es obligatoria.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado solo puede ser Activo.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);


        //Si la validacion da error
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422); 
        }

        // Si la validación pasa correctamente, creamos un nuevo empleado
        $employee = Employee::create([
            'first_last_name' => $request->first_last_name,
            'second_last_name' => $request->second_last_name,
            'first_name' => $request->first_name,
            'other_names' => $request->other_names,
            'country' => $request->country,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'email' => $request->email,
            'entry_date' => $request->entry_date,
            'area' => $request->area,
            'status' => $request->status
        ]);
    
        if ($employee) {
            // Empleado creado exitosamente
            return response()->json([
                'message' => 'Empleado creado correctamente',
                'employee' => $employee
            ], 201);
        } else {
            // Error al crear el empleado
            return response()->json([
                'message' => 'Error al crear el empleado',
            ], 500);
        }
    
    }

    // Método para obtener un empleado por su ID
    public function show($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            // Si no se encuentra el empleado
            return response()->json([
                'message' => 'Empleado no encontrado',
            ], 404);
        }

        // Si se encuentra el empleado
        return response()->json([
            'message' => 'Empleado encontrado correctamente',
            'employee' => $employee,
        ], 200);
    }

    // Método para actualizar un empleado
    public function update(Request $request, $id)
    {
        // Buscar el empleado por su ID
        $employee = Employee::find($id);

        if (!$employee) {
            // Si no se encuentra el empleado
            return response()->json([
                'message' => 'Empleado no encontrado',
            ], 404);
        }

        // Reglas de validación para la actualización
        $rules = [
            'first_last_name' => 'regex:/^[A-Z]+$/|max:20',
            'second_last_name' => 'regex:/^[A-Z]+$/|max:20',
            'first_name' => 'regex:/^[A-Z]+$/|max:20',
            'other_names' => 'regex:/^[A-Z\s]+$/|max:50',
            'country' => 'in:Colombia,United States',
            'id_type' => '',
            'id_number' => 'unique:employees,id_number,' . $id . '|max:20',
            'email' => 'email|unique:employees,email,' . $id . '|max:300',
            'entry_date' => 'date|before_or_equal:today|after_or_equal:' . now()->subMonth(),
            'area' => '',
            'status' => 'in:Active',
        ];

        // Mensajes personalizados para las reglas de validación
        $messages = [
            // Mensajes personalizados aquí...
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // Si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422); 
        }

        // Actualizar los datos del empleado
        $employee->update($request->all());

        // Devolver respuesta de éxito
        return response()->json([
            'message' => 'Empleado actualizado correctamente',
            'employee' => $employee
        ], 200);
    }

    // Método para eliminar un empleado
    public function destroy($id)
    {
        // Buscar el empleado por su ID
        $employee = Employee::find($id);

        if (!$employee) {
            // Si no se encuentra el empleado
            return response()->json([
                'message' => 'Empleado no encontrado',
            ], 404);
        }

        // Eliminar el empleado
        $employee->delete();

        // Devolver respuesta de éxito
        return response()->json([
            'message' => 'Empleado eliminado correctamente',
            'employee removed' => $employee
        ], 200);
    }
}
