<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'surnom' => 'required|unique:clients,surnom',
            'telephone' => ['required', 'unique:clients,telephone', 'regex:/^(70|76|77|78)\d{7}$/'],
            'adresse' => 'nullable|string',
            'user.nom' => 'required_with:user|string',
            'user.prenom' => 'required_with:user|string',
            'user.telephone' => [
                'required_with:user',
                Rule::unique('users', 'telephone')->ignore($this->telephone, 'telephone'),
                'regex:/^(70|76|77|78)\d{7}$/'
            ],
            'user.role_id' => 'required_with:user|exists:roles,id',
            'user.login' => 'required_with:user|unique:users,login',
            'user.password' => ['required_with:user', 'min:5', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{5,}$/'],
            'user.active' => 'boolean',
            'user.photo' => 'nullable|image'
        ];
    }

    public function messages()
    {
        return [
            'surnom.required' => 'Le surnom est obligatoire.',
            'surnom.unique' => 'Ce surnom est déjà utilisé.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'telephone.regex' => 'Le format du numéro de téléphone est invalide.',
            'user.nom.required_with' => 'Le nom est obligatoire lors de la création d\'un compte utilisateur.',
            'user.prenom.required_with' => 'Le prénom est obligatoire lors de la création d\'un compte utilisateur.',
            'user.telephone.required_with' => 'Le numéro de téléphone est obligatoire lors de la création d\'un compte utilisateur.',
            'user.telephone.unique' => 'Ce numéro de téléphone est déjà utilisé par un autre compte utilisateur.',
            'user.telephone.regex' => 'Le format du numéro de téléphone de l\'utilisateur est invalide.',
            'user.role_id.required_with' => 'Le rôle est obligatoire lors de la création d\'un compte utilisateur.',
            'user.role_id.exists' => 'Le rôle sélectionné n\'existe pas.',
            'user.login.required_with' => 'Le login est obligatoire lors de la création d\'un compte utilisateur.',
            'user.login.unique' => 'Ce login est déjà utilisé.',
            'user.password.required_with' => 'Le mot de passe est obligatoire lors de la création d\'un compte utilisateur.',
            'user.password.min' => 'Le mot de passe doit contenir au moins 5 caractères.',
            'user.password.regex' => 'Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial.',
        ];
    }
}
