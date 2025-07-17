<!DOCTYPE html>
<html>
<head>
    <title>Lista de Classificados</title>
    <style>
        th {
            border: 1px solid #000; text-align: center; padding: 4px; background-color: grey;
        }
        td {
            border: 1px solid #000; text-align: center; padding: 4px;
        }
    </style>
</head>
<body>
    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th>Instituição</th>
                <th>UF</th>
                <th>Município</th>
                <th>Data da Solicitação</th>
                <th>Classificação</th>
            </tr>
        </thead>
        <tbody>
        @if (count($data))
            @foreach($data as $classificado)
                <tr>
                    <td>{{ $classificado->instituicao?->razao_social }}</td>
                    <td>{{ $classificado->instituicao?->estado?->sigla }}</td>
                    <td>{{ $classificado->instituicao?->cidade?->cidade }}</td>
                    <td>{{ $classificado->created_at }}</td>
                    <td>{{ $classificado->classificacao ? ($classificado->classificacao . 'º') : '-' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5">Nenhum registro encontrado</td>
            </tr>
        @endif
        </tbody>
    </table>
</body>
</html>