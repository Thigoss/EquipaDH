<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Nome Social</th>
            <th>CPF</th>
            <th>Data de Nascimento</th>
            <th>Unidade</th>
            <th>Instuição</th>
            <th>E-mail</th>
            <th>Tipo de Usuário</th>
            <th>Perfil</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $user)
        <tr>
            <td>{{ $user['nome'] }}</td>
            <td>{{ $user['nome_social'] }}</td>
            <td>{{ $user['cpf'] }}</td>
            <td>{{ $user['data_nascimento'] }}</td>
            <td>
                @if (count($user['perfis']) > 0)
                    @foreach($user['perfis'] as $perfil)
                        {{ isset($perfil['unidade']) ? $perfil['unidade']['nome']  : '-'}} <br>
                    @endforeach
                @else
                    {{ '-' }}
                @endif
            </td>
            <td>
                @if (count($user['instituicoes']) > 0)
                    @foreach($user['instituicoes'] as $instituicao)
                        {{ $instituicao['razao_social'] }} <br>
                    @endforeach
                @else
                    {{ '-' }}
                @endif
            </td>
            <td>{{ $user['email'] }}</td>
            <td>{{ $user['tipo'] === 'I' ? 'Interno' : 'Externo' }}</td>
            <td>
                @if (count($user['perfis']) > 0)
                    @foreach($user['perfis'] as $perfil)
                        {{ $perfil['perfil']['nome'] }}<br>
                    @endforeach
                @else
                    {{ '-' }}
                @endif
            </td>
            <td>{{ $user['ativo'] ? 'Ativo' : 'Inativo' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>