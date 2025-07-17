<p>Olá, {{$solicitacao->nome}}</p>

<p>Informamos que a sua solicitação de credenciamento foi reprovada.</p>

<p>Para consultar a justificativa de reprovação, consulte o status do seu credenciamento em: <a href="https://equipadh.mdh.gov.br/" target="_blank"> https://equipadh.mdh.gov.br/ </a>, clique no botão de ação de "Visualizar Histórico", em seguida expanda a justificativa no botão com o ícone de lupa.</p>

@if (isset($observacao) && $observacao)
<p>Justificativa: {{$observacao}}</p>
@endif

<p>Dúvidas sobre o Programa EquipaDH+? </p>

<p>Consulte o nosso FAQ: <a href="https://www.gov.br/mdh/pt-br/programa-de-equipagem-equipadh/FAQ/perguntas-frequentes" target="_blank"> Perguntas Frequentes </a> </p>

<p>Contato: <a href="mailto:equipadh@mdh.gov.br">equipadh@mdh.gov.br</a></p>

<p>Equipe EQUIPADH</p>