<html>
    <table>
        <thead>
            <tr>
                <th>Histórico do Usuário</th>
            </tr>
            <br>
            <br>
            <tr>
                <td><b>Dados da Ação</td>
            </tr>
            <tr>
                <td>Quem fez</td>
                <td>{{($usuario['usuario_nome']) ? $usuario['usuario_nome'] : '-'}}</td>
            </tr>
            <tr>
                <td>O que fez</td>
                <td>{{($usuario['acao_realizada']) ? $usuario['acao_realizada'] : '-'}}</td>
            </tr>
            <tr>
                <td>Quando fez</td>
                <td>{{($usuario['created_at']) ? $usuario['created_at'] : '-'}}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Dados do Usuário</td>
            </tr>
            <tr>
                <td>CPF</td>
                <td>{{($usuario['cpf']) ? $usuario['cpf'] : '-'}}</td>
            </tr>
            <tr>
                <td>RG</td>
                <td>{{($usuario['rg']) ? $usuario['rg'] : '-'}}</td>
            </tr>
            <tr>
                <td>Nome</td>
                <td>{{($usuario['nome']) ? $usuario['nome'] : '-'}}</td>
            </tr>
            <tr>
                <td>Data de Nascimento</td>
                <td>{{($usuario['data_nascimento']) ? $usuario['data_nascimento'] : '-'}}</td>
            </tr>
            <tr>
                <td>Instrução</td>
                <td>{{($usuario['instrucao_descricao']) ? $usuario['instrucao_descricao'] : '-'}}</td>
            </tr>
            <tr>
                <td>Perfil</td>
                <td>{{($usuario['perfis']) ? $usuario['perfis'][0]['perfil_nome'] : '-'}}</td>
            </tr>
            <tr>
                <td>E-mail</td>
                <td>{{($usuario['email']) ? $usuario['email'] : '-'}}</td>
            </tr>
            <tr>
                <td>Celular</td>
                <td>{{($usuario['celular']) ? $usuario['celular'] : '-'}}</td>
            </tr>
            <tr>
                <td>Telefone</td>
                <td>{{($usuario['telefone']) ? $usuario['telefone'] : '-'}}</td>
            </tr>
            <tr>
                <td>Cargo</td>
                <td>{{($usuario['cargo']) ? $usuario['cargo'] : '-'}}</td>
            </tr>
            <tr>
                <td>CEP</td>
                <td>{{($usuario['cep']) ? $usuario['cep'] : '-'}}</td>
            </tr>
            <tr>
                <td>Estado</td>
                <td>{{($usuario['estado_nome']) ? $usuario['estado_nome'] : '-'}}</td>
            </tr>
            <tr>
                <td>Cidade</td>
                <td>{{($usuario['cidade_nome']) ? $usuario['cidade_nome'] : '-'}}</td>
            </tr>
            <tr>
                <td>Bairro</td>
                <td>{{($usuario['bairro_nome']) ? $usuario['bairro_nome'] : '-'}}</td>
            </tr>
            <tr>
                <td>Endereço</td>
                <td>{{($usuario['endereco']) ? $usuario['endereco'] : '-'}}</td>
            </tr>
            <tr>
                <td>Número</td>
                <td>{{($usuario['numero']) ? $usuario['numero'] : '-'}}</td>
            </tr>
            <tr>
                <td>Número</td>
                <td>{{($usuario['complemento']) ? $usuario['complemento'] : '-'}}</td>
            </tr>
            <tr>
                <td><b>Detalhes da avaliação</td>
            </tr>
            @foreach($avaliacoes as $avaliacao)
                <tr>
                    <td>Status Geral</td>
                    <td>{{($avaliacao['status']) ? $avaliacao['status'] : '-'}}</td>
                </tr>
                <tr>
                    <td>Período</td>
                    <td>
                        @php  $periodo = '-'  @endphp
                        @if (isset($avaliacao['data_inicial']) && isset($avaliacao['data_final']))
                        @php $periodo = date('d/m/Y', strtotime($avaliacao['data_inicial'])) . ' até ' . date('d/m/Y', strtotime($avaliacao['data_final'])); @endphp
                        @endif
                        {{ $periodo }}
                    </td>
                </tr>
                <tr>
                    <td>Justificativa</td>
                    <td>{{($avaliacao['justificativa']) ? $avaliacao['justificativa'] : '-'}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</html>