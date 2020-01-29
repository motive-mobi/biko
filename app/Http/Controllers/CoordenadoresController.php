<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Coordenadores;
use App\Nucleo;
use App\User;
use Illuminate\Support\Facades\Hash;
use Image;
use Session;

class CoordenadoresController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {
      $user = Auth::user();
      Session::put('verified', $user->email_verified_at);

      if($user->role === 'aluno'){
        return back();
      }

      if($user->role === 'professor'){
        $coordenadores = Coordenadores::get();

        return view('coordenadores')->with([
          'coordenadores' => $coordenadores,
          'user' => $user,
        ]);
      }

      if($user->role === 'coordenador'){
        $coordenadores = Coordenadores::get();

        return view('coordenadores')->with([
          'coordenadores' => $coordenadores,
          'user' => $user,
        ]);
      }

      if($user->role === 'administrador'){
        $user = Auth::user();
        $coordenadores = Coordenadores::where('Status', 1)->get();
        if($coordenadores->isEmpty()){
          $coordenadores = Coordenadores::where('Status', 0)->get();
        }

        return view('coordenadores')->with([
          'user' => $user,
          'coordenadores' => $coordenadores,
        ]);
      }
    }

    public function showForm()
    {
      $nucleos = Nucleo::get()->where('Status', 1);

      return view('coordenadoresCreate')->with('nucleos', $nucleos);
    }

    public function create(Request $request)
    {
      $Foto = $request->file('inputFoto');
      //$Extension = $Foto->getClientOriginalExtension();
      $today = \Carbon\Carbon::now();

      $cgu = $request->input('inputRepresentanteCGU');
      if($cgu){
        $nucleo = $request->input('inputNucleo');
        $representantesNucleo = Coordenadores::where('id_nucleo', $nucleo)->count();
        if($representantesNucleo >= 2){
          return back()->withInput()->with('error', 'NÚCLEO JÁ POSSUI 2 REPRESENTANTES.');
        }elseif($representantesNucleo != ''){
          $dados = Nucleo::find($nucleo);
          $id = $coordenador->id;
          $dados->id_representanteCGU = $id;
          $dados->save();
        }
      }

      $user = User::where('email', $request->input('inputEmail'))->first();
      if (!$user) {
        $user = User::create([
          'name' => $request->input('inputNomeCoordenador'),
          'email' => $request->input('inputEmail'),
          'password' => Hash::make('uneafro@2019'),
          'role' => 'coordenador',
          'email_verified_at' => $today,
        ]);
      }else{
        return back()->with([
          'error' => 'ESTE EMAIL JÁ ESTÁ EM USO',
        ]);
      }

      if($Foto){
        $Extension = $Foto->getClientOriginalExtension();
        $foto = $Foto->getFilename() . '.' . $Extension;
      }else{
        $foto = null;
      }

      $coordenador = Coordenadores::create([
        'id_user' => $user->id,
        'Status' => $request->input('inputStatus'),
        'NomeCoordenador' => $request->input('inputNomeCoordenador'),
        'id_nucleo' => $request->input('inputNucleo'),
        'FuncaoCoordenador' => $request->input('inputFuncaoCoordenador'),
        'AnoIngresso' => $request->input('inputAnoIngresso'),
        'RepresentanteCGU' => $request->input('inputRepresentanteCGU'),
        //'Foto' => $Foto->getFilename() . '.' . $Extension,
        'Foto' => $foto,
        'CPF' => $request->input('inputCPF'),
        'RG' => $request->input('inputRG'),
        'Raca' => $request->input('inputRaca'),
        'Genero' => $request->input('inputGenero'),
        'EstadoCivil' => $request->input('inputEstadoCivil'),
        'Nascimento' => $request->input('inputNascimento'),
        'Endereco' => $request->input('inputEndereco'),
        'Numero' => $request->input('inputNumero'),
        'Bairro' => $request->input('inputBairro'),
        'CEP' => $request->input('inputCEP'),
        'Cidade' => $request->input('inputCidade'),
        'Estado' => $request->input('inputEstado'),
        'Complemento' => $request->input('inputComplemento'),
        'FoneComercial' => $request->input('inputFoneComercial'),
        'FoneResidencial' => $request->input('inputFoneResidencial'),
        'FoneCelular' => $request->input('inputFoneCelular'),
        'Email' => $request->input('inputEmail'),
        'Empresa' => $request->input('inputEmpresa'),
        'EnderecoEmpresa' => $request->input('inputEnderecoEmpresa'),
        'NumeroEmpresa' => $request->input('inputNumeroEmpresa'),
        'ComplementoEmpresa' => $request->input('inputComplementoEmpresa'),
        'BairroEmpresa' => $request->input('inputBairroEmpresa'),
        'CidadeEmpresa' => $request->input('inputCidadeEmpresa'),
        'EstadoEmpresa' => $request->input('inputEstadoEmpresa'),
        'CEPEmpresa' => $request->input('inputCEPEmpresa'),
        'Cargo' => $request->input('inputCargo'),
        'HorarioFrom' => $request->input('inputHorarioFrom'),
        'HorarioTo' => $request->input('inputHorarioTo'),
        'ProjetosRealizados' => $request->input('inputProjetosRealizados'),
        'ProjetosNome' => $request->input('inputProjetosNome'),
        'ProjetosFuncao' => $request->input('inputProjetosFuncao'),
        'ComoSoube' => $request->input('inputComoSoube'),
        'ComoSoubeOutros' => $request->input('inputComoSoubeOutros'),
        'MotivoPrincipal' => $request->input('inputMotivoPrincipal'),
        'EnsinoSuperior' => $request->input('inputEnsinoSuperior'),
        'InstituicaoSuperior' => $request->input('inputInstituicaoSuperior'),
        'CursoSuperior1' => $request->input('inputCursoSuperior1'),
        'AnoCursoSuperior1' => $request->input('inputAnoCursoSuperior1'),
        'CursoSuperior2' => $request->input('inputCursoSuperior2'),
        'AnoCursoSuperior2' => $request->input('inputAnoCursoSuperior2'),
        'Especializacao' => $request->input('inputEspecializacao'),
        'InstEspecializacao' => $request->input('inputInstEspecializacao'),
        'CursoEspecializacao' => $request->input('inputCursoEspecializacao'),
        'AnoCursoEspecializacao' => $request->input('inputAnoCursoEspecializacao'),
        'Mestrado' => $request->input('inputMestrado'),
        'InstMestrado' => $request->input('inputInstMestrado'),
        'CursoMestrado' => $request->input('inputCursoMestrado'),
        'AnoCursoMestrado' => $request->input('inputAnoCursoMestrado'),
      ]);

      if($Foto){
        $filename = $Foto->getFilename().'.'.$Foto->getClientOriginalExtension();
        $path = public_path('storage/'.$filename);
        $image = $request->file('inputFoto');

        Image::make($image->getRealPath())
          ->resize(150, null, function ($constraint) {
              $constraint->aspectRatio();
          })
          ->crop(110,110,null,null)
          ->encode('jpg',80)
          ->save($path);
      }

      return back()->with('success', 'DADOS SALVOS COM SUCESSO.');
    }

    public function edit($id)
    {
      $dados = Coordenadores::find($id);
      //dd($dados);
      $nucleos = Nucleo::get()->where('Status', 1);

      return view('coordenadoresEdit')->with([
        'dados' => $dados,
        'nucleos' => $nucleos,
      ]);
    }

    public function update(Request $request, $id)
    {
      $dados = Coordenadores::find($id);

      $Foto = $request->file('inputFoto');
      if($Foto){
        $Extension = $Foto->getClientOriginalExtension();
      }

      $dados->NomeCoordenador = $request->input('inputNomeCoordenador');
      $dados->id_nucleo = $request->input('inputNucleo');
      if($Foto){
        $dados->Foto = $Foto->getFilename() . '.' . $Extension;
      }
      $dados->FuncaoCoordenador = $request->input('inputFuncaoCoordenador');
      $dados->AnoIngresso = $request->input('inputAnoIngresso');
      $dados->RepresentanteCGU = $request->input('inputRepresentanteCGU');
      $dados->CPF = $dados->CPF;
      $dados->RG = $request->input('inputRG');
      $dados->Raca = $request->input('inputRaca');
      $dados->Genero = $request->input('inputGenero');
      $dados->EstadoCivil = $request->input('inputEstadoCivil');
      $dados->Nascimento = $request->input('inputNascimento');
      $dados->Endereco = $request->input('inputEndereco');
      $dados->Numero = $request->input('inputNumero');
      $dados->Bairro = $request->input('inputBairro');
      $dados->CEP = $request->input('inputCEP');
      $dados->Cidade = $request->input('inputCidade');
      $dados->Estado = $request->input('inputEstado');
      $dados->Complemento = $request->input('inputComplemento');
      $dados->FoneComercial = $request->input('inputFoneComercial');
      $dados->FoneResidencial = $request->input('inputFoneResidencial');
      $dados->FoneCelular = $request->input('inputFoneCelular');
      $dados->Email = $request->input('inputEmail');
      $dados->Empresa = $request->input('inputEmpresa');
      $dados->EnderecoEmpresa = $request->input('inputEnderecoEmpresa');
      $dados->NumeroEmpresa = $request->input('inputNumeroEmpresa');
      $dados->ComplementoEmpresa = $request->input('inputComplementoEmpresa');
      $dados->BairroEmpresa = $request->input('inputBairroEmpresa');
      $dados->CidadeEmpresa = $request->input('inputCidadeEmpresa');
      $dados->EstadoEmpresa = $request->input('inputEstadoEmpresa');
      $dados->CEPEmpresa = $request->input('inputCEPEmpresa');
      $dados->Cargo = $request->input('inputCargo');
      $dados->HorarioFrom = $request->input('inputHorarioFrom');
      $dados->HorarioTo = $request->input('inputHorarioTo');
      $ProjetosRealizados = $dados->ProjetosRealizados = $request->input('inputProjetosRealizados');
      if($ProjetosRealizados === 'nao'){
        $dados->ProjetosNome = NULL;
        $dados->ProjetosFuncao = NULL;
      }else{
        $dados->ProjetosNome = $request->input('inputProjetosNome');
        $dados->ProjetosFuncao = $request->input('inputProjetosFuncao');
      }
      $dados->ComoSoube = $request->input('inputComoSoube');
      if($request->input('inputComoSoube') != 'outros'){
        $dados->ComoSoubeOutros = NULL;
      }else{
        $dados->ComoSoubeOutros = $request->input('inputComoSoubeOutros');
      }
      $dados->MotivoPrincipal = $request->input('inputMotivoPrincipal');
      $dados->EnsinoSuperior = $request->input('inputEnsinoSuperior');
      $dados->InstituicaoSuperior = $request->input('inputInstituicaoSuperior');
      $dados->CursoSuperior1 = $request->input('inputCursoSuperior1');
      $dados->AnoCursoSuperior1 = $request->input('inputAnoCursoSuperior1');
      $dados->CursoSuperior2 = $request->input('inputCursoSuperior2');
      $dados->AnoCursoSuperior2 = $request->input('inputAnoCursoSuperior2');
      $dados->Especializacao = $request->input('inputEspecializacao');
      $dados->InstEspecializacao = $request->input('inputInstEspecializacao');
      $dados->CursoEspecializacao = $request->input('inputCursoEspecializacao');
      $dados->AnoCursoEspecializacao = $request->input('inputAnoCursoEspecializacao');
      $dados->Mestrado = $request->input('inputMestrado');
      $dados->InstMestrado = $request->input('inputInstMestrado');
      $dados->CursoMestrado = $request->input('inputCursoMestrado');
      $dados->AnoCursoMestrado = $request->input('inputAnoCursoMestrado');

      $cgu = $dados->RepresentanteCGU;
      if($cgu){
        $nucleo = $dados->id_nucleo;
        $representantesNucleo = Coordenadores::where('id_nucleo', $nucleo)->count();
        $idRepresentantes = Coordenadores::where('id_nucleo', $nucleo)->get('id');

        if($representantesNucleo >= 2 && $idRepresentantes[0]['id'] != $dados->id && $idRepresentantes[1]['id'] != $dados->id){
          return back()->with('error', 'NÚCLEO JÁ POSSUI 2 REPRESENTANTES.');
        }
      }

      if($Foto){
        $filename = $Foto->getFilename().'.'.$Foto->getClientOriginalExtension();
        $path = public_path('storage/'.$filename);
        $image = $request->file('inputFoto');

        Image::make($image->getRealPath())
          ->resize(150, null, function ($constraint) {
              $constraint->aspectRatio();
          })
          ->crop(110,110,null,null)
          ->encode('jpg',80)
          ->save($path);
      }

      $currentEmail = Coordenadores::where('id_user', $dados->id_user)->pluck('Email');
      $inputEmail = $request->input('inputEmail');
      if($inputEmail !== $currentEmail[0]){
        try {
          $coordenador = Coordenadores::where('Email',$inputEmail)->get('Email');
          $user = User::where('id', $dados->id_user)->first();
          $user->email = $inputEmail;
          $user->save();
          //dd($professor);
        } catch (ModelNotFoundException $exception) {
            return back()->with([
              'error' => 'ESTE EMAIL JÁ ESTÁ EM USO.',
            ]);
        }
      }

      $dados->save();

      return back()->with('success', 'DADOS SALVOS COM SUCESSO.');
    }

    public function disable(Request $request, $id)
    {
      $coordenador = Coordenadores::find($id);
      $coordenador->Status = 0;

      $coordenador->save();

      return back()->with('success', 'Coordenador inativado com sucesso.');
    }

    public function enable(Request $request, $id)
    {
      $coordenador = Coordenadores::find($id);
      $coordenador->Status = 1;

      $coordenador->save();

      return back()->with('success', 'Coordenador ativado com sucesso.');
    }

    public function search(Request $request)
    {
      $status = $request->input('status');
      $cpf = $request->input('cpf');

      if($cpf != ''){
        $result = Coordenadores::where('CPF', $cpf)->count();
        if($result > 0){
          return \Response::json(true);
        }elseif($result === 0){
          return \Response::json(false);
        }
      }

      if($status === '0'){
        $user = Auth::user();
        $result = Coordenadores::where('Status', 0)->get();
        if($result->isEmpty()){
          return redirect('coordenadores')->with([
            'error' => 'Não há coordenadores inativos no momento.',
          ]);
        }

        return view('coordenadores')->with([
          'user' => $user,
          'coordenadores' => $result,
        ]);
      }

      if($status === '1'){
        $user = Auth::user();
        $result = Coordenadores::where('Status', 1)->get();
        if($result->isEmpty()){
          return redirect('coordenadores')->with([
            'error' => 'Não há coordenadores ativos no momento.',
          ]);
        }

        return view('coordenadores')->with([
          'user' => $user,
          'coordenadores' => $result,
        ]);
      }

      $query = $request->input('inputQuery');
      $results = Coordenadores::where('NomeCoordenador','LIKE','%'.$query.'%')->get();

      if($results->isEmpty()){
        return back()->with('error', 'Nenhum resultado encontrado.');
      }else{
        return view('coordenadores')->with('coordenadores', $results);
      }
    }

    public function details($id)
    {
      $dados = Coordenadores::find($id);
      $nucleos = Nucleo::get()->where('Status', 1);

      return view('coordenadoresDetails')->with([
        'dados' => $dados,
        'nucleos' => $nucleos,
      ]);
    }
}