@extends('laravel-usp-theme::master')

@section('content')
    @include('flash')
    <a href="{{route('cursos_hab.create')}}" class="btn btn-success mb-3">Cadastrar Curso e Habilitação x Departamento de Ensino</a>

    <div class="alert alert-info"><b>Nota:</b> Somente para vincular alunos de graduação à um departamento de ensino.</div>

    <h3>Cursos e Habilitações x Departamentos de Ensino</h3>
    <table class="table table-striped table-bordered" id="cursos_cadastrados">
        <thead>
            <tr>
                <th>Codcur / Codhab / Curso / Habilitação / Período da Habilitação</th>
                <th>Departamento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cursos_cadastrados as $curso)
                <tr>
                    <td>{{$curso->codcur}} / {{$curso->codhab}} / {{$curso->nomcur}} / {{$curso->nomhab}} / {{$curso->perhab}}</td>
                    <td>{{$curso->setor->nomabvset}}</td>
                    <td>
                        <a href="{{route('cursos_hab.edit', $curso->id)}}" class="btn btn-warning col-auto float-left"><i class="fas fa-pencil-alt"></i></a>
                        <form method="POST" style="width:42px;" class="float-left col-auto" action="{{route('cursos_hab.destroy', $curso->id)}}">
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
            new DataTable('#cursos_cadastrados');
        });
    </script>
@endsection