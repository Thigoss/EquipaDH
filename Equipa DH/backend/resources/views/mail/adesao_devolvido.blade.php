<p>Olá, {{$user->nome}}</p>

<p>Informamos que a solicitação nº {{$adesao->id}} da sua instituição foi devolvida para ajustes.</p>

@if (isset($observacao) && $observacao)
<p>Justificativa: {{$observacao}}</p>
@endif

<p>Para consultar a justificativa de devolução no sistema, consulte o status em: <a href="https://equipadh.mdh.gov.br/" target="_blank">https://equipadh.mdh.gov.br/</a>, clique no botão de ação de "Visualizar Histórico", em seguida expanda a justificativa no botão com o ícone de lupa.</p>

<p>Dúvidas sobre o Programa EquipaDH+? </p>

<p>Consulte o nosso FAQ: <a href="https://www.gov.br/mdh/pt-br/programa-de-equipagem-equipadh/FAQ/perguntas-frequentes" target="_blank"> Perguntas Frequentes </a> </p>

<p>Contato: <a href="mailto:equipadh@mdh.gov.br">equipadh@mdh.gov.br</a></p>

<p>Equipe EQUIPADH</p>