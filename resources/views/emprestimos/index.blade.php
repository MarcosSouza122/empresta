@extends('laravel-usp-theme::master')

@section('content')
@include('flash')
@inject('pessoa','App\Utils\ReplicadoUtils')

    <div class="card">
        <div class="card-header"><b>Itens emprestados</b></div>
        <div class="card-body">
            <form method="GET" action="emprestimos">
                <div class="row">
                    <div class="col-sm" id="busca">
                        <input type="text" class="form-control" name="busca" value="{{ Request()->busca }}" placeholder="Digite o código do material">
                    </div>
                    <div class=" col-auto">
                        <button type="submit" class="btn btn-success">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Código</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Data do empréstimo</th>
                <th>Nº USP</th>
                <th>Pessoa</td>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
        @foreach($emprestimos as $emprestimo)
            <tr>
                <td>{{ $emprestimo->material->codigo }}</td>
                <td>{{ $emprestimo->material->categoria->nome }}</td>
                <td>{{ $emprestimo->material->descricao }}</td>
                <td>{{ Carbon\Carbon::parse($emprestimo->data_emprestimo)->format('d/m/Y H:i') }}</td>
                @if($emprestimo->visitante_id == null)
                    <td>{{ $emprestimo->username }}</td>    
                    <td> 
                        {{ implode(', ',$pessoa::pessoaUSP($emprestimo->username)) }}       
                        <br><b>Vacinação covid-19</b>: {{ \Uspdev\Replicado\Pessoa::obterSituacaoVacinaCovid19($emprestimo->username) }}
                    </td>
                @else
                    <td>&nbsp;</td>    
                    <td>{{ $emprestimo->visitante->nome }}, 
                        {{ $emprestimo->visitante->email }} <br>    
                        <i class="fas fa-phone"></i> {{ $emprestimo->visitante->telefone }}
                    </td>    
                @endif                
                <td class="form-inline">
                    @include('emprestimos.partials.devolver-btn')
                    <a href="emprestimos/{{$emprestimo->id}}" class="btn btn-primary col-auto ml-2"><i class="fa fa-eye"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $emprestimos->appends(request()->query())->links() }}
@endsection('content')
