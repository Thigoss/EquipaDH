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
            <th>Data</th>
            <th>Nome Social</th>
            <th>Data de Atualização</th>
            <th>UF</th>
            <th>Instituição</th>
            <th>Política Pública</th>
            <th>Cronograma</th>
            <th>Data de início</th>
            <th>Data de encerramento</th>
            <th>Tipo de solicitação</th>
            <th>Situação da Solicitação</th>
            <th>Fase</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $adesao)
        <tr>
            <td>{{ $adesao['id'] }}</td>
            <td>{{ $adesao['created_at'] }}</td>
            <td>{{ $adesao['updated_at'] }}</td>
            <td>{{ $adesao['instituicao']['estado']['sigla'] }}</td>
            <td>{{ $adesao['instituicao']['cnpj'] . ' - ' . $adesao['instituicao']['razao_social']}}</td>
            <td>{{ $adesao['cronograma']['programa']['nome'] }}</td>
            <td>{{ $adesao['cronograma']['numero'] }}</td>
            <td>{{ $adesao['cronograma']['data_publicacao_inicio'] }}</td>
            <td>{{ $adesao['cronograma']['data_divulgacao_lista'] }}</td>
            <td>
                {{ $adesao['tipo'] == 'AD' ? 'Adesão' : '' }}
                {{ $adesao['tipo'] == 'RA' ? 'Recurso de Adesão' : '' }}
                {{ $adesao['tipo'] == 'RC' ? 'Recurso de Classificação' : '' }}
                {{ $adesao['tipo'] == 'CV' ? 'Convocação' : '' }}
            </td>
            <td>
                @if($adesao['situacao'] == 'PE')
                    Pendente
                @elseif($adesao['situacao'] == 'DV')
                    Devolvido
                @elseif($adesao['situacao'] == 'RE')
                    Recusado
                @elseif($adesao['situacao'] == 'AP')
                    Aprovado
                @elseif($adesao['situacao'] == 'RC')
                    Recurso
                @endif
            </td>
            <td>
                @if($adesao['cronograma']['fase'] == 'NI')
                    Não Iniciado
                @elseif($adesao['cronograma']['fase'] == 'PU')
                    Publicado
                @elseif($adesao['cronograma']['fase'] == 'AD')
                    Adesão e Habilitação
                @elseif($adesao['cronograma']['fase'] == 'AF')
                    Validação das Adesões
                @elseif($adesao['cronograma']['fase'] == 'DA')
                    Divulgação da Adesão
                @elseif($adesao['cronograma']['fase'] == 'RA')
                    Recurso Adesão
                @elseif($adesao['cronograma']['fase'] == 'RF')
                    Validação dos Recursos
                @elseif($adesao['cronograma']['fase'] == 'DH')
                    Divulgação de Habilitados
                @elseif($adesao['cronograma']['fase'] == 'RH')  
                    Recurso de Habilitados
                @elseif($adesao['cronograma']['fase'] == 'HF')  
                    Validação de Recursos de Habilitados
                @elseif($adesao['cronograma']['fase'] == 'DL')
                    Divulgação da Lista
                @elseif($adesao['cronograma']['fase'] == 'EN')  
                    Encerrado
                @elseif($adesao['cronograma']['fase'] == 'CO')
                    Convocação
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>