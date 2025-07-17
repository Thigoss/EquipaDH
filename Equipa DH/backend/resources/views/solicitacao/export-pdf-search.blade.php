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
            <th>Solicitação</th>
            <th>Data</th>
            <th>Data de Atualização</th>
            <th>CPF</th>
            <th>Nome</th>
            <th>UF</th>
            <th>Instituição</th>
            <th>E-mail</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $solicitacao)
        <tr>
            <td>{{ $solicitacao['id'] }}</td>
            <td>{{ $solicitacao['created_at'] }}</td>
            <td>{{ $solicitacao['updated_at'] }}</td>
            <td>{{ $solicitacao['cpf'] }}</td>
            <td>{{ $solicitacao['nome'] }}</td>
            <td>
                @if(isset($solicitacao['instituicao']))
                    {{ $solicitacao['instituicao']['estado']['sigla'] }}
                @elseif(isset($solicitacao['solicitacao_instituicao']))
                    {{ $solicitacao['solicitacao_instituicao'][0]['estado']['sigla'] }}
                @else
                    Não informado
                @endif
            </td>
            <td>
                @if(isset($solicitacao['instituicao']))
                    {{ $solicitacao['instituicao']['cnpj'] . ' - ' . $solicitacao['instituicao']['razao_social'] }}
                @elseif(isset($solicitacao['solicitacao_instituicao']))
                    {{ $solicitacao['solicitacao_instituicao'][0]['cnpj'] . ' ' . $solicitacao['solicitacao_instituicao'][0]['razao_social'] }}
                @else
                    Não informado
                @endif
            </td>
            <td>{{ $solicitacao['email'] }}</td>
            <td>
                @if($solicitacao['situacao'] == 'PE')
                    Pendente
                @elseif($solicitacao['situacao'] == 'AP')
                    Aprovado
                @elseif($solicitacao['situacao'] == 'RE')
                    Reprovado
                @elseif($solicitacao['situacao'] == 'DE')
                    Devolvido
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>