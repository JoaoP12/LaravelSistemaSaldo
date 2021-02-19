@extends('adminlte::page')

@section('title', 'Histórico de Movimentações')

@section('content_header')
    <h1>Histórico de Movimentações</h1>

    <ol class="breadcrumb">
        <li><a href="">Dashboard</a></li>
        <li><a href="">Histórico</a></li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <form action="{{ route('history.search')}}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
                <input type="text" name="id" class="form-control" placeholder="ID">
                <input type="date" name="date" class="form-control">
                <select name="type" class="form-control">
                    <option value="">--- Selecione o tipo ---</option>
                    @foreach($types as $key => $type)
                        <option value="{{ $key }}">{{ $type }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Pesquisar</button>
            </form>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>?Sender?</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $hist)
                        <tr>
                            <td>{{ $hist->id }}</td>
                            <td>{{ number_format($hist->amount, 2 , '.', '') }}</td>
                            <td>{{ $hist->type($hist->type) }}</td>
                            <td>{{ $hist->date }}</td>
                            <td>
                                @if($hist->user_id_transaction)
                                    {{ $hist->userSender->name }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
            @if (isset($dataForm))
                {!! $history->appends($dataForm)->links() !!}
            @else
                {!! $history->links() !!}
            @endif
            
            
        </div>
    </div>
@stop