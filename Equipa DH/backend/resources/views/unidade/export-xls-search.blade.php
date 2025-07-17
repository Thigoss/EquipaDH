<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
<thead>
        <tr>
            <th>Nome</th>
            <th>Estado</th>
            <th>Cidade</th>
            <th>CEP</th>
            <th>Endereço</th>
            <th>Bairro</th>
            <th>Número</th>
            <th>Complemento</th>
            <th>Ativo</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $unidade)
        <tr>
            <td>{{ $unidade['nome'] }}</td>
            <td>{{ $unidade['estado']['sigla'] }}</td>
            <td>{{ $unidade['cidade']['cidade'] }}</td>
            <td>{{ $unidade['cep'] }}</td>
            <td>{{ $unidade['endereco'] }}</td>
            <td>{{ $unidade['bairro'] }}</td>
            <td>{{ $unidade['numero'] }}</td>
            <td>{{ $unidade['complemento'] }}</td>
            <td>{{ $unidade['ativo'] ? 'Ativo' : 'Inativo' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>