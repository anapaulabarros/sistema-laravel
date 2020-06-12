<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;
use App\User;
use Validator;
use App\DocumentsUser;
use App\OccupationUser;

class UserController extends Controller{

    public function index(Request $request){
    
        $users = User::where('id', '!=', Auth::user()->id)->get();    
        return view('users.index', compact('users'));
    }

    public function documents(Request $request) {

        $documents = DocumentsUser::where('user_id', $request->id)->get();
        return view('users.documents', compact('documents'));
    }

    public function profile(Request $request){
    
        return view('users.profile');
    }

    public function newUser(){
    
        return view('users.new');
    }


    public function info(Request $request) {

        $user = User::where('id', $request->id)->first();
        return view('users.info', compact('user'));

    }

    public function infoProfessionals(Request $request) {

        $user = User::where('id', $request->id)->first();
        $info = $user->occupation;
      
        return view('users.info_professionals', compact('info'));

    }

    /**
    * Method to store a new client
    * 
    * @param   Illuminate\Http\Request $request
    * @return  @return  Illuminate\Http\Response 
    *
    */    
    public function createUser(Request $request) {

        $arrayDataUser = [];

        $verifyIfAlreadyRegistered = User::searchByEmail($request->email);
        if ($verifyIfAlreadyRegistered) {
            return response()->json(['success' => false, 'error' => 'Já existe um cadastro com este e-mail. Você pode logar, ou redefinir sua senha.'], 403);
        }

        if ($request->file('user_image')) {
            $image = '';
            $guessExtension         = $request->file('user_image')->guessExtension();
            $allowedImageExtensions = ['jpg','png','jpeg','svg','jfif','gif'];

            // Verify if the extension is valid to upload
            if (!in_array($guessExtension, $allowedImageExtensions)) {
                return response()->json(['success' => false, 'error' => 'Formato de arquivo não permitido.'], 403);
            }

            $imageKey         = Str::random(35);
            $exists           = Storage::disk('public')->exists("images/customers{$imageKey}.{$guessExtension}");

            while ($exists) {
                $imageKey     = Str::random(35);
                $exists       = Storage::disk('public')->exists("images/customers{$imageKey}.{$guessExtension}");
            }

            $image            = $request->file('user_image')->storeAs('public/images/customers', "{$imageKey}.{$guessExtension}");
            $arrayDataUser['photo']      = $image;
        }

        $arrayDataUser = [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'number' => $request->number,
            'city' => $request->city,
            'state' => $request->state,
            'neighborhood' => $request->neighborhood,
            'zip_code'     => $request->zip_code,
            'document'  => null,
            'birthday'     => $request->birthday
        ];

        $user = User::createNewUser($arrayDataUser);

        return response()->json($user);
    }

    /**
    * Method to update info general of client
    * 
    * @param   Illuminate\Http\Request $request
    * @return  @return  Illuminate\Http\Response 
    *
    */    
    public function updatePersonalData(Request $request) {

        $user    = User::find($request->id_user);

        if(!$user){
            return response()->json(['error', 'Usuário não encontrado']);
        }

        $user->name = $request->name;
        if($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->phone_number = $request->phone_number;
        $user->updated_at = date('Y-m-d H:s:i');

        $user->update();

        return response()->json($user);
    }

    /**
    * Method to update info address of client
    * 
    * @param   Illuminate\Http\Request $request
    * @return  @return  Illuminate\Http\Response 
    *
    */  
    public function updateAdrressData(Request $request) {

        $user    = User::find($request->id_user);

        if(!$user){
            return response()->json(['error', 'Usuário não encontrado']);
        }
        
        $user->address  = $request->address;
        $user->number   = $request->number;
        $user->neighborhood = $request->neighborhood;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip_code = $request->zip_code;   

        $user->update();

        return response()->json($user);
    }

    /**
    * Method to delete client of storage
    * 
    * @param   Illuminate\Http\Request $request
    * @return  @return  Illuminate\Http\Response 
    *
    */  
    public function delete(Request $request) {
        $user = User::destroy($request->user_id);
        return response()->json($user);
    }

    /**
    * Method to update info professionals of client
    * 
    * @param   Illuminate\Http\Request $request
    * @return  @return  Illuminate\Http\Response 
    *
    */  
    public function updateProfessionalsData(Request $request) {

        $user    = User::find($request->id_user);
        $info_profesional = OccupationUser::findByUser($user);
        if(!$info_profesional) {
            return response()->json(['error', 'Dados profissionais não encontrado']);
        }

        $info_profesional->funcao = $request->funcao;
        $info_profesional->number_doc_license = $request->number_doc_license;
        $info_profesional->phone = $request->phone_number;
        $info_profesional->updated_at = date('Y-m-d H:s:i');

        $info_profesional->update();

        return response()->json($info_profesional);

    }
    
    /**
    * Method to update info address professional of client
    * 
    * @param   Illuminate\Http\Request $request
    * @return  @return  Illuminate\Http\Response 
    *
    */
    public function updateAddressProfessionalData(Request $request) {

        $user    = User::find($request->id_user);
        $info_address_profesional = OccupationUser::findByUser($user);

        if(!$info_address_profesional) {
            return response()->json(['error', 'Dados de endereço profissionais não encontrado']);
        }
        
        $info_address_profesional->address = $request->address;
        $info_address_profesional->number = $request->number;
        $info_address_profesional->city = $request->city;
        $info_address_profesional->state = $request->state;
        $info_address_profesional->district = $request->district;
        $info_address_profesional->zip_code = $request->zip_code;
        $info_address_profesional->complement = $request->complement;
        $info_address_profesional->updated_at = date('Y-m-d H:s:i');

        $info_address_profesional->update();

        return response()->json($info_address_profesional);
    }

    /**
    * Method to delete document of client
    * 
    * @param   Illuminate\Http\Request $request
    * @return  @return  Illuminate\Http\Response 
    *
    */  
    public function deleteDocument(Request $request) {

        $user    = User::find($request->user_id);
        $document_user = DocumentsUser::findByUser($user);

        if(!$document_user) {
            return response()->json(['error', 'Documento não localizado']);
        }

        $document = DocumentsUser::destroy($request->document_id_del);
        return response()->json($document);
    }


    public function storeDocument(Request $request) {

        $existingDoc = DocumentsUser::where('user_id', $request->user_id)->where('type', $request->document_type)->first();

        if ($existingDoc) {
            # update the current doc
            $newDoc = $existingDoc;
        } else {
            # create a new doc into table
            $newDoc = new DocumentsUser();
        }

        # set type of doc
        $newDoc->type = $request->document_type;
        # set the  user_id
        $newDoc->user_id = $request->user_id;
        #get the doc send by user
        $document = $request->file('file'); 
        $url = '';

        # verify of extension
        $guessExtension = $document->guessExtension();
        $allowedImageExtensions = ['jpg','png','jpeg','svg','jfif','gif','pdf'];

        # Check if the extension is valid
        if (!in_array($guessExtension, $allowedImageExtensions)) {
            return response()->json(['success' => false, 'error' => 'Formato de arquivo não permitido.'], 403);
        } else {
            if ($guessExtension == "pdf") {
                $url = $this->storePdfDocument($document, $guessExtension);
            } else {
                $url = $this->storeImageDocument($document, $guessExtension);
            }
        }
        $newDoc->status = 0;
        $newDoc->url = $url;
        $newDoc->save();

        return \App::make('url')->to('/'.$newDoc->url);
    }

    # Method to save pdf into storage
    public function storePdfDocument($document, $extension)
    {
        $pdf = '';
        $pdfKey         = Str::random(35);
        $exists           = Storage::disk('public')->exists("documents{$pdfKey}.{$extension}");
        while ($exists) {
            $pdfKey     = Str::random(35);
            $exists       = Storage::disk('public')->exists("documents{$pdfKey}.{$extension}");
        }
        $pdf            = $document->storeAs('storage/documents', "{$pdfKey}.{$extension}");

        return $pdf;
    }

    # Method to save imagens into storage
    public function storeImageDocument($document, $extension)
    {
        $image = '';
        $imageKey         = Str::random(35);
        $exists           = Storage::disk('public')->exists("images/documents{$imageKey}.{$extension}");
        while ($exists) {
            $imageKey     = Str::random(35);
            $exists       = Storage::disk('public')->exists("images/documents{$imageKey}.{$extension}");
        }
        $image            = $document->storeAs('public/images/documents', "{$imageKey}.{$extension}");

        return $image;
    }


    public function fechUploadDocuments(Request $request)
    {
        
        //TO DO: render a new form to upload the document
    }
}