<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th>Razão Social</th>
            <th>CNPJ</th>
            <th>E-mail</th>
            <th>Esfera</th>
            <th>Telefone</th>
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
    @foreach($data as $instituicao)
        <tr>
            <td>{{ $instituicao['razao_social'] }}</td>
            <td>{{ $instituicao['cnpj'] }}</td>
            <td>{{ $instituicao['email'] }}</td>
            <td>{{ $instituicao['esfera'] }}</td>
            <td>{{ $instituicao['telefone'] }}</td>
            <td>{{ $instituicao['estado'] ? $instituicao['estado']['sigla'] : '' }}</td>
            <td>{{ $instituicao['cidade'] ? $instituicao['cidade']['cidade'] : '' }}</td>
            <td>{{ $instituicao['cep'] }}</td>
            <td>{{ $instituicao['endereco'] }}</td>
            <td>{{ $instituicao['bairro'] }}</td>
            <td>{{ $instituicao['numero'] }}</td>
            <td>{{ $instituicao['complemento'] }}</td>
            <td>{{ $instituicao['ativo'] ? 'Ativo' : 'Inativo' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

