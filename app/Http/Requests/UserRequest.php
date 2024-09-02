<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => [
                'required',
                'string',
                'unique:users,telephone',
                'regex:/^(70|76|77|78)\d{7}$/'
            ],
            'role_id' => 'required|exists:roles,id',
            'login' => 'required|string|unique:users,login',
            'password' => [
                'required',
                'string',
                'min:5',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{5,}$/'
            ],
            'active' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ];
    }

    public function messages()
    {
        return [
            'telephone.regex' => 'Le numéro de téléphone doit commencer par 70, 76, 77 ou 78 et contenir 9 chiffres.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'login.unique' => 'Ce login est déjà utilisé.',
            'password.regex' => 'Le mot de passe doit contenir au moins 5 caractères, incluant des majuscules, des minuscules, des chiffres et des caractères spéciaux.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'L\'image doit être de type : jpeg, png, jpg, gif.',
            'photo.max' => 'L\'image ne doit pas dépasser 2Mo.',
            'role_id.exists' => 'Le rôle sélectionné n\'existe pas.',
            'active.boolean' => 'La valeur de l\'attribut active doit être un booléen.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'Error',
            'message' => 'Erreur de validation',
            'data' => $validator->errors()
        ], 422));
    }
}
