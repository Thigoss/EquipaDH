<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Situação</th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $bemEquipamento)
        <tr>
            <td>{{ $bemEquipamento['id'] }}</td>
            <td>{{ $bemEquipamento['nome'] }}</td>
            <td>{{ $bemEquipamento['categoria'] }}</td>
            <td>{{ $bemEquipamento['situacao'] }}</td>
            <td>{{ $bemEquipamento['descricao'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>