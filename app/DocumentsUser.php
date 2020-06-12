<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentsUser extends Model
{

    public $statusLabel = [
         0 => ['label' => 'Pendente', 'class' => 'warning'],
         1 => ['label' => 'Aprovado', 'class' => 'success'],
         2 => ['label' => 'Recusado', 'class' => 'danger'],
         3 => ['label' => 'Vazio', 'class' => 'secondary'],
    ];

    public function getLabel($documentType){
        $labels = [
            'CPF' => 'CPF',
            'RG' => 'RG',
            'COREN' => 'COREN',
            'CRM' => 'CRM',
            'CRO' => 'CRO',
            'CARTEIRA_MOTORISTA' => 'CARTEIRA DE MOTORISTA',
        ];
        return $labels[$documentType];
    }

    public function getStatusLabel(){
        if ($this->url == "") {
            return $this->statusLabel[3];
        }
        return $this->statusLabel[$this->status];
    }

    public function createDefaultDocument($type)
    {
        $doc = new DocumentsUser();
        $doc->type = $type;
        $doc->status = 0;
        $doc->url = "";
        $doc->user_id = auth()->user()->id;
        $doc->save();
        return $doc;
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    # Busca um documento pelo usuario
    public static function searchByUserID(User $user)
    {
        return DocumentsUser::where('user_id', $user->id)->first();
    }

}
