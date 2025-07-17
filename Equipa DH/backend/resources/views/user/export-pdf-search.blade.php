<style>
    th {
        border:1px solid #000; text-align: center;padding: 4px; background-color: grey;
    }
    td {
        border:1px solid #000; text-align: center;padding: 4px;
    }
</style>
<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>E-mail</th>
            <th>Perfil</th>
            <th>Ativo</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $user)
        <tr>
            <td>{{ $user['nome'] }}</td>
            <td>{{ $user['cpf'] }}</td>
            <td>{{ $user['email'] }}</td>
            <td>
                @foreach($user['perfis'] as $perfil)
                    {{ $perfil['perfil']['nome'] }}<br>
                @endforeach
            </td>
            <td>{{ $user['ativo'] ? 'Ativo' : 'Inativo' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>