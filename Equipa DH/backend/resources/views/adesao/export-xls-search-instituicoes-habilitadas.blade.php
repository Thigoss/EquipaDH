<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table>
    <thead>
        <tr>
            <th>Nº Adesão</th>
            <th>Cronograma</th>
            <th>Política Pública</th>
            <th>UF</th>
            <th>Município</th>
            <th>Instituição</th>
            <th>Classificação</th>
            <th>Esfera</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $adesao)
        <tr>
            <td>{{ $adesao['id'] }}</td>
            <td>{{ $adesao['cronograma']['numero'] }}</td>
            <td>{{ $adesao['cronograma']['programa']['nome'] }}</td>
            <td>{{ $adesao['instituicao']['estado']['sigla'] }}</td>
            <td>{{ $adesao['instituicao']['cidade']['cidade'] }}</td>
            <td>{{ $adesao['instituicao']['cnpj'] }} - {{ $adesao['instituicao']['razao_social']}}</td>
            <td>{{ $adesao['classificacao'] }}</td>
            <td>{{ !empty($adesao['instituicao']['esfera']) ? $esferas[$adesao['instituicao']['esfera']] : '' }}</td>
            <td>{{ $adesao['convocado'] ? 'Convocado' : 'Pendente de convocação' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>