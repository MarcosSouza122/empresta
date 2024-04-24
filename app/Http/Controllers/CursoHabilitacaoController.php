<?php

namespace App\Http\Controllers;

use App\Models\CursoHabilitacao;
use App\Models\Setor;
use Illuminate\Http\Request;
use Uspdev\Replicado\Graduacao;

class CursoHabilitacaoController extends Controller
{
    public function __construct() {
        $this->middleware('can:manager');
    }

    public function index(){
        $cursos_cadastrados = CursoHabilitacao::all();

        return view('cursos_hab.index')->with([
            'cursos_cadastrados' => $cursos_cadastrados->isEmpty() ? array() : $cursos_cadastrados
        ]);
    }

    public function create(){
        $cursos_hab = Graduacao::obterCursosHabilitacoes(getenv('REPLICADO_CODUNDCLG'));
        $departamentos_ensino = Graduacao::listarDepartamentosDeEnsino();

        return view('cursos_hab.create')->with([
            'cursos_hab' => $cursos_hab,
            'departamentos_ensino' => $departamentos_ensino,
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'curso_hab' => 'required',
            'departamento_ensino' => 'required',
        ]);

        $departamento_decode = json_decode($validated['departamento_ensino']);

        foreach ($validated['curso_hab'] as $curso_json) {
            $curso_hab_decode = json_decode($curso_json);

            if(!is_null(CursoHabilitacao::where('codcur', $curso_hab_decode->codcur)->where('codhab', $curso_hab_decode->codhab)->first())){
                session()->flash('alert-danger', 'Este curso e habilitação já estão cadastrados em um departamento!');
                return back();
            }

            $setor = Setor::firstOrCreate(
                ['codset' => $departamento_decode->codset],
                ['nomabvset' => $departamento_decode->nomabvset, 'nomset' => $departamento_decode->nomset]
            );

            $curso_hab = new CursoHabilitacao();
            $curso_hab->codcur = $curso_hab_decode->codcur;
            $curso_hab->nomcur = $curso_hab_decode->nomcur;
            $curso_hab->codhab = $curso_hab_decode->codhab;
            $curso_hab->nomhab = $curso_hab_decode->nomhab;
            $curso_hab->perhab = $curso_hab_decode->perhab;
            $curso_hab->setor()->associate($setor);
            $curso_hab->save();
        }

        if(count($validated['curso_hab']) > 1)
            session()->flash('alert-success', 'Cursos e Habilitações cadastrados no departamento com sucesso!');
        else 
            session()->flash('alert-success', 'Curso e Habilitação cadastrado no departamento com sucesso!');
        return redirect()->route('cursos_hab.index');
    }

    public function edit(CursoHabilitacao $curso){
        $cursos_hab = Graduacao::obterCursosHabilitacoes(getenv('REPLICADO_CODUNDCLG'));
        $departamentos_ensino = Graduacao::listarDepartamentosDeEnsino();

        return view('cursos_hab.edit')->with([
            'curso' => $curso,
            'cursos_hab' => $cursos_hab,
            'departamentos_ensino' => $departamentos_ensino,
        ]);
    }

    public function update(Request $request, CursoHabilitacao $curso){
        $validated = $request->validate([
            'curso_hab' => 'required',
            'departamento_ensino' => 'required',
        ]);
        
        $curso_hab_decode = json_decode($validated['curso_hab']);
        $departamento_decode = json_decode($validated['departamento_ensino']);

        $departamento = Setor::firstOrCreate(
            ['codset' => $departamento_decode->codset],
            ['nomabvset' => $departamento_decode->nomabvset, 'nomset' => $departamento_decode->nomset]
        );

        $curso->codcur = $curso_hab_decode->codcur;
        $curso->nomcur = $curso_hab_decode->nomcur;
        $curso->codhab = $curso_hab_decode->codhab;
        $curso->nomhab = $curso_hab_decode->nomhab;
        $curso->perhab = $curso_hab_decode->perhab;
        $curso->setor()->associate($departamento);
        $curso->save();

        session()->flash('alert-success', 'Curso e Habilitação alterado com sucesso!');
        return redirect()->route('cursos_hab.index');
    }

    public function destroy(CursoHabilitacao $curso){
        $curso->delete();
        session()->flash('alert-success', 'Curso e Habilitação excluído com sucesso!');
        return redirect()->route('cursos_hab.index');
    }
}
