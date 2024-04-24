<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriaRequest;
use App\Models\Material;
use App\Models\Setor;
use App\Models\Vinculo;
use PDF;
use \Picqer\Barcode\BarcodeGeneratorPNG;
use Uspdev\Replicado\Estrutura;

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manager');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Categoria::orderBy('nome','asc');

        if($request->busca != null){
            $query->where('nome', 'LIKE', "%$request->busca%");
        }
        $categorias = $query->paginate(50);
        if ($categorias->count() == null) {
            $request->session()->flash('alert-danger', 'Não há registros!');
        }
        return view('categorias.index')->with('categorias',$categorias);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categorias.create')->with([
            'categoria' => new Categoria,
            'setores' => Estrutura::listarSetores(),
            'vinculos' => Vinculo::all(),
            'vinculos_permitidos' => array(),
            'setores_permitidos' => array()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaRequest $request)
    {
        $validated = $request->validated();
        $categoria = Categoria::create($validated);

        $vinculos_permitidos = $request->input('vinculos_permitidos');
        $setores_permitidos = $request->input('setores_permitidos');

        if(!is_null($vinculos_permitidos)){
            foreach($vinculos_permitidos as $vinculo_id){
                $vinculo = Vinculo::find($vinculo_id);

                $categoria->vinculos()->attach($vinculo);
            }
        }

        if(!is_null($setores_permitidos)){
            foreach($setores_permitidos as $setor_permitido){
                $setor_json = json_decode($setor_permitido);

                $setor = Setor::firstOrCreate(
                    ['codset' => $setor_json->codset],
                    ['nomabvset' => $setor_json->nomabvset, 'nomset' => $setor_json->nomset]
                );

                $categoria->setores()->attach($setor);
            }
        }

        return redirect("categorias/$categoria->id");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        return view('categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit')->with([
            'categoria' => $categoria,
            'setores' => Estrutura::listarSetores(),
            'vinculos' => Vinculo::all(),
            'vinculos_permitidos' => $categoria->vinculos->pluck('id')->all(),
            'setores_permitidos' => $categoria->setores->pluck('codset')->all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriaRequest $request, Categoria $categoria)
    {
        $validated = $request->validated();
        $categoria->update($validated);

        $vinculos_permitidos = $request->input('vinculos_permitidos');
        $setores_permitidos = $request->input('setores_permitidos');

        $categoria->vinculos()->detach();
        if(!is_null($vinculos_permitidos)){
            foreach($vinculos_permitidos as $vinculo_id){
                $vinculo = Vinculo::find($vinculo_id);

                $categoria->vinculos()->attach($vinculo);
            }
        }

        $categoria->setores()->detach();
        if(!is_null($setores_permitidos)){
            foreach($setores_permitidos as $setor_permitido){
                $setor_json = json_decode($setor_permitido);

                $setor = Setor::firstOrCreate(
                    ['codset' => $setor_json->codset],
                    ['nomabvset' => $setor_json->nomabvset, 'nomset' => $setor_json->nomset]
                );

                $categoria->setores()->attach($setor);
            }
        }

        return redirect("categorias/$categoria->id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        if ($categoria->materials->isNotEmpty()){
            return redirect('/categorias')->with('alert-danger', 'Categoria ainda contém materiais. Por favor delete materiais antes!');
        }
        $categoria->delete();
        return redirect('/categorias');
    }

    public function barcode(){
        $this->authorize('balcao');
        $categorias = Categoria::orderBy('nome', 'asc')->get();
        return view('categorias.barcode')->with('categorias', $categorias);
    }

    public function barcodes(Request $request)
    {
        $generator = new BarcodeGeneratorPNG();
        $materiais = Material::orderBy('codigo', 'asc');
        if($request->categoria_id[0] == null){
            $categorias = Categoria::orderBy('nome', 'asc')->get();
            foreach($categorias as $categoria){
                $materiais->orWhere('categoria_id',$categoria->id);
            }
        }
        else{
            foreach($request->categoria_id as $categoria){
                $materiais->orWhere('categoria_id',$categoria);
            }
        }
        $materiais = $materiais->get();
        // Lógica temporária para gerar códigos de barras com 6 ou 3 códigos em cada linha
        $n = count($materiais);
        $trs = '';
        $cols = (int) $request->formatacao; // 3 ou 6
        for($i=0; $i < floor($n/$cols)*$cols; $i = $i+$cols){
            $tr = '<tr>';
            for($j=0; $j < $cols; $j++){
                $code = $materiais[$i+$j]->codigo;
                $descricao = $materiais[$i+$j]->descricao;
                $barcode = base64_encode($generator->getBarcode($code,$generator::TYPE_CODE_128));
                if($cols == 3)
                    $tr .= "<td> <div style='width: 230px; margin: 0 auto'> {$descricao}</div>{$code} <br> <img src='data:image/png;base64,{$barcode}' width='120' style='margin-bottom: 10px'></td>";
                else
                    $tr .= "<td><img src='data:image/png;base64,{$barcode}' width='80'> <br> {$code}</td>";
            }
            $tr .= '</tr>';
            $trs .= $tr;
        }
        // Faltantes
        $tr = '<tr>';
        for($i = floor($n/$cols)*$cols; $i < $n; $i++){
            $code = $materiais[$i]->codigo;
            $descricao = $materiais[$i]->descricao;
            $barcode = base64_encode($generator->getBarcode($code,$generator::TYPE_CODE_128));
            if($cols == 3)
                $tr .= "<td> <div style='width: 230px; margin: 0 auto'> {$descricao}</div>{$code} <br> <img src='data:image/png;base64,{$barcode}' width='120' style='margin-bottom: 10px'></td>";
            else
                $tr .= "<td><img src='data:image/png;base64,{$barcode}' width='80'> <br> {$code}</td>";
        }
        $faltantes = str_repeat("<td>Null</td>", 3 - $n%3);
        $tr .= $faltantes;
        $tr .= '</tr>';
        $trs .= $tr;
        $pdf = PDF::loadView("categorias.pdfs.barcodes", compact('trs'))->setPaper('A4', 'portrait');
        return $pdf->download("barcodes.pdf");
    }

}
