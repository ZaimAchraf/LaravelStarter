<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class ValidationHelper extends Controller
{
    public static function validateNewUser(Request $request)
    {
        return $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.unique' => 'Le nom d\'utilisateur est déjà utilisé.',
            'email.unique' => 'L\'adresse email est déjà utilisée.',
            'password.require' => 'le mot de passe est obligatoire pour creer un compte.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.'
        ]);
    }

    public static function validateSimpleClient(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ], [
            'name.required' => 'Le nom du client est requis.',
            'phone.required' => 'Le numéro Telephone est requis.',
        ]);
    }

    public static function validateEntrepriseClient(Request $request, $id)
    {
        return $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'ICE' => 'required|string|max:255|unique:clients,ICE' . ($id ? (',' . $id) : ''),
            'phone_contact' => 'required|string|max:255',
        ], [
            'nom_entreprise.required' => 'Le nom de l\'entreprise est requis.',
            'ICE.required' => 'Le numéro ICE est requis.',
            'ICE.unique' => 'Ce numéro ICE existe déjà dans la BD. Le numéro ICE doit etre unique.',
            'phone_contact.required' => 'Le téléphone de contact est requis.',
        ]);
    }

    public static function validateProduct(Request $request, $id)
    {
        return $request->validate([
            'label' => 'required|string|max:255',
            'ref' => 'string|unique:products,ref'  . ($id ? (',' . $id) : ''),
        ], [
            'label.required' => 'La description de la ligne de commande est requise.',
            'ref.unique' => 'Cette reference existe déjà dans la BD. La reference doit etre unique.',
        ]);
    }

    public static function validateNewProvider(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ], [
            'name.required' => 'Le nom du fournisseur est requis.',
            'phone.required' => 'Le numéro Telephone est requis.',
        ]);
    }

    public static function validateNewProviderQuotation(Request $request, $index)
    {
        return $request->validate([
            'lines.' . $index . '.provider_name' => 'required|string|max:255',
            'lines.' . $index . '.provider_phone' => 'required|string|max:255',
        ], [
            'lines.' . $index . '.provider_name.required' => 'Le nom du fournisseur est requis.',
            'lines.' . $index . '.provider_phone.required' => 'Le numéro Telephone est requis.',
        ]);
    }
}
