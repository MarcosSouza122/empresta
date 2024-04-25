<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Requests\MaterialRequest;
use App\Models\Categoria;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manager', ['except' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('balcao');

        $query = Material::orderBy('codigo','asc');

        if($request->busca != null){
            $query->where('codigo', '=', "$request->busca");
        }
        $materials = $query->paginate(50);
        if ($materials->count() == null) {
            $request->session()->flash('alert-danger', 'Não há registros!');
        }
        return view('materials.index')->with('materials',$materials);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Categoria $categoria = null)
    {
        $material = new Material;
        if(!is_null($categoria)) $material->categoria()->associate($categoria);
        return view('materials.create')->with('material', $material);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaterialRequest $request)
    {
        $validated = $request->validated();
        $validated['dias_da_semana'] = (int) !is_null($request->input('dias_da_semana'));
        $validated['created_by_id']= auth()->user()->id;
        $material = Material::create($validated);
        return redirect("materials/$material->id");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        return view('materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        return view('materials.edit')->with('material', $material);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(MaterialRequest $request, Material $material)
    {
        $validated = $request->validated();
        $validated['dias_da_semana'] = (int) !is_null($request->input('dias_da_semana'));
        $material->update($validated);
        return redirect("materials/$material->id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        if($material->emprestimos()->exists()){
            session()->flash('alert-danger', 'Este material não pode ser excluído pois já foi emprestado anteriormente.');
            return back();
        }

        $material->delete();
        return redirect('materials');
    }
}
