@extends('laravel-usp-theme::master')

@section('content')
    @include('flash')
    <div class="card mb-5">
        <div class="card-header"><b>Cadastro Curso e Habilitação x Departamento de Ensino</b></div>
        <div class="card-body">
            <form action="{{route('cursos_hab.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label><b>Curso / Habilitação / Período da Habilitação</b></label>
                    <select name="curso_hab" class="curso_hab form-control" required>
                        @foreach ($cursos_hab as $curso_hab)
                            <option value='{"codcur": {{$curso_hab['codcur']}}, "codhab": {{$curso_hab['codhab']}}, "nomcur": "{{$curso_hab['nomcur']}}", "nomhab": "{{$curso_hab['nomhab']}}", "perhab": "{{$curso_hab['perhab']}}"}'>{{$curso_hab['codcur'] . " " . $curso_hab['nomcur']}} / {{$curso_hab['codhab'] . " " . $curso_hab['nomhab']}} / {{$curso_hab['perhab']}}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Os números mostrados são o código do curso e o código da habilitação respectivamente.</small>
                </div>

                <div class="form-group">
                    <label><b>Departamento de Ensino</b></label>
                    <select name="departamento_ensino" class="departamento_ensino form-control" required>
                        @foreach ($departamentos_ensino as $departamento_ensino)
                            <option value='{"codset": {{$departamento_ensino['codset']}}, "nomabvset": "{{$departamento_ensino['nomabvset']}}"}'>{{$departamento_ensino['nomabvset']}}</option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-success">Enviar</button>
            </form>
        </div>
    </div>

    <h3>Lista de Cursos Cadastrados</h3>
    <table class="table table-striped table-bordered" id="cursos_cadastrados">
        <thead>
            <tr>
                <th>Curso / Habilitação / Período da Habilitação</th>
                <th>Departamento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cursos_cadastrados as $curso)
                <tr>
                    <td>{{$curso->codcur . " " . $curso->nomcur}} / {{$curso->codhab . " " . $curso->nomhab}} / {{$curso->perhab}}</td>
                    <td>{{$curso->departamento->nomabvset}}</td>
                    <td>
                        <a href="#" class="btn btn-warning col-auto float-left"><i class="fas fa-pencil-alt"></i></a>
                        <form method="POST" style="width:42px;" class="float-left col-auto" action="#">
                            @csrf 
                            @method('delete')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Você tem certeza que deseja apagar?')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td>Não há cursos cadastrados</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection

@section('javascripts_bottom')
    <script>
        $(document).ready(function(){
            $('.curso_hab').select2({
                theme: 'bootstrap4',
                language: 'pt-BR'
            });

            $('.departamento_ensino').select2({
                theme: 'bootstrap4',
                language: 'pt-BR'
            });

            new DataTable('#cursos_cadastrados');
        });

        // coloca o focus no select2
        // https://stackoverflow.com/questions/25882999/set-focus-to-search-text-field-when-we-click-on-select-2-drop-down
         $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
    </script>
@endsection