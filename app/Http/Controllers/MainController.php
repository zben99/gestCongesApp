<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Mail\Contact;
use Illuminate\Http\Request;
use App\Models\FormulaireContact;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreFormulaireContactRequest;

class MainController extends Controller
{
    public function home()
    {
        $posts = Post::latest()->take(3)->get();
        return view('user.home', compact("posts"));
    }

    public function pageProduit()
    {
        return view('user.produit');
    }

    public function pageActualite()
    {
        $posts = Post::latest()->get();

        return view('user.actualite', compact("posts"));
    }

    public function pageActualiteSingle($post)
    {
        $post = Post::findOrFail($post);

        return view('user.actualite_single', compact("post"));
    }

    public function pagePropos()
    {
        return view('user.propos');
    }

    public function pageContact()
    {

        return view('user.contact');
    }

    public function saveContact(StoreFormulaireContactRequest $request)
    {
      // Retrieve the validated input data
      $validated = $request->validated();

    //enregistrement dans la bd

      $contact=new FormulaireContact();
      $contact->nom = $validated['name'] ;
      $contact->email= $validated['email'];
      $contact->sujet= $validated['objet'];
      $contact->message=$validated['message'];
      $contact->save();



     // Mail::to("ben98nana@gmail.com")->send(new Contact($validated));


     $varEnvoi = "success"; // variable de controle pour message d'envois du formulaire

     return redirect()->route('contact')->with(compact('varEnvoi'));

    }

}
