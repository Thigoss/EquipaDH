<html>
    <table>
        <thead>
            <tr>
                <th>Histórico do Perfil</th>
            </tr>
            <br>
            <br>
            <tr>
                <td><b>Dados da Ação</td>
            </tr>
            <tr>
                <td>Quem fez</td>
                <td>{{($historico['usuario_nome']) ? $historico['usuario_nome'] : '-'}}</td>
            </tr>
            <tr>
                <td>O que fez</td>
                <td>{{($historico['acao_realizada']) ? $historico['acao_realizada'] : '-'}}</td>
            </tr>
            <tr>
                <td>Quando fez</td>
                <td>{{($historico['created_at']) ? $historico['created_at'] : '-'}}</td>
            </tr>
        </thead>
        <br><br><br>
        <tbody>
            <tr>
                <td><strong>Alterações</strong></td>
            </tr>
            <tr>
                <td>Nome: </td>
                <td>{{ $historico['dados']['nome']}}</td>
            </tr>
            <tr>
                <td>Descrição: </td>
                <td>{{ $historico['dados']['descricao']}}</td>
            </tr>
            <tr>
                <td>Situação: </td>
                <td>{{ $historico['dados']['ativo'] ? 'Ativo' : 'Inativo'}}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="table table-bordered" border="1" style="width: 100%">
                      <thead>
                        <tr>
                          <th>Funcionalidade</th>
                          <th>Ações</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($historico['dados']['recursos'] as $dado)
                        <tr>
                          <td>{{ $dado['nome'] }}</td>
                          <td>
                            @foreach($dado['acoes'] as $dado2)
                                {{ $dado2['nome'] }}<br>
                            @endforeach
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</html>