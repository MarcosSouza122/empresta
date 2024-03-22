@extends('laravel-usp-theme::master')

@section('content')
    <div class="card mb-5">
        <div class="card-header"><b>Cadastro Curso e Habilitação x Departamento de Ensino</b></div>
        <div class="card-body">
            <form action="#" method="POST">
                @csrf
                <div class="form-group">
                    <label><b>Curso / Habilitação / Período da Habilitação</b></label>
                    <select name="curso_hab" class="curso_hab form-control">
                        @foreach ($cursos_hab as $curso_hab)
                            <option value="{{$curso_hab['codcur']}}_{{$curso_hab['codhab']}}">{{$curso_hab['codcur'] . " " . $curso_hab['nomcur']}} / {{$curso_hab['codhab'] . " " . $curso_hab['nomhab']}} / {{$curso_hab['perhab']}}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Os números mostrados são o código do curso e o código da habilitação respectivamente.</small>
                </div>

                <div class="form-group">
                    <label><b>Departamento de Ensino</b></label>
                    <select name="departamento_ensino" class="departamento_ensino form-control">
                        @foreach ($departamentos_ensino as $departamento_ensino)
                            <option value="{{$departamento_ensino['codset']}}">{{$departamento_ensino['nomabvset']}}</option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-success">Enviar</button>
            </form>
        </div>
    </div>

    <table class="table table-striped" id="cursos_cadastrados">
        <thead>
            <tr>
                <th>Curso/Habilitação</th>
                <th>Departamento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cursos_cadastrados as $curso)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
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