/* eslint-disable eqeqeq */
import Vue from 'vue'
import moment from 'moment'

const prepararCpf = function (cpf) {
  cpf = cpf.charAt(0) + cpf.charAt(1) + cpf.charAt(2) +
    '.' + cpf.charAt(3) + cpf.charAt(4) + cpf.charAt(5) +
    '.' + cpf.charAt(6) + cpf.charAt(7) + cpf.charAt(8) +
    '-' + cpf.charAt(9) + cpf.charAt(10)
  return cpf
}

Vue.filter('prepararCpf', prepararCpf)

const prepararRg = function (rg) {
  rg = rg.charAt(0) + rg.charAt(1) +
    '.' + rg.charAt(2) + rg.charAt(3) + rg.charAt(4) +
    '.' + rg.charAt(5) + rg.charAt(6) + rg.charAt(7) +
    '-' + rg.charAt(8)
  return rg
}

Vue.filter('prepararRg', prepararRg)

const prepararCodigoCarteirinha = function (codigo) {
  codigo = codigo.charAt(0) + codigo.charAt(1) + codigo.charAt(2) +
    codigo.charAt(3) + codigo.charAt(4) + codigo.charAt(5) +
    codigo.charAt(6) + codigo.charAt(7) + codigo.charAt(8) +
    '-' + codigo.charAt(9) + codigo.charAt(10)
  return codigo
}

Vue.filter('prepararCodigoCarteirinha', prepararCodigoCarteirinha)

const prepararCep = function (cep) {
  cep = cep.replace(/\D/g, '')
  cep = cep.charAt(0) + cep.charAt(1) + cep.charAt(2) + cep.charAt(3) + cep.charAt(4) +
    '-' + cep.charAt(5) + cep.charAt(6) + cep.charAt(7)
  return cep
}

Vue.filter('prepararCep', prepararCep)

const acoesHistorico = function (value) {
  if (value == 0) {
    return 'Desativação de Usuário'
  } else if (value == 1) {
    return 'Ativação de Usuário'
  } else if (value == 2) {
    return 'Edição de Usuário'
  } else if (value == 3) {
    return 'Cadastro de Usuário'
  } else if (value == 4) {
    return 'Carteirinha Vencida'
  }
}

Vue.filter('acoesHistorico', acoesHistorico)

const meioComunicacaoString = function (value) {
  if (value == 0) {
    return 'Verbal'
  } else if (value == 1) {
    return 'Não verbal'
  } else if (value == 2) {
    return 'Braille'
  } else if (value == 3) {
    return 'Língua brasileira de sinais'
  } else if (value == 4) {
    return 'PECS (Sistema de Comunicação por Troca de Figuras)'
  } else if (value == 5) {
    return 'Outras tecnologias ou meios'
  }
}

Vue.filter('meioComunicacaoString', meioComunicacaoString)

const racaString = function (value) {
  if (value == 0) {
    return 'Amarela'
  } else if (value == 1) {
    return 'Branca'
  } else if (value == 2) {
    return 'Indígena'
  } else if (value == 3) {
    return 'Parda'
  } else if (value == 4) {
    return 'Preta'
  }
}

Vue.filter('racaString', racaString)

const tipoSanguineoString = function (value) {
  if (value == 0) {
    return 'A+'
  } else if (value == 1) {
    return 'A-'
  } else if (value == 2) {
    return 'B+'
  } else if (value == 3) {
    return 'B-'
  } else if (value == 4) {
    return 'O+'
  } else if (value == 5) {
    return 'O-'
  } else if (value == 6) {
    return 'AB+'
  } else if (value == 7) {
    return 'AB-'
  }
}

Vue.filter('tipoSanguineoString', tipoSanguineoString)

const tipoResponsavelString = function (value) {
  if (value == 0) {
    return 'Tutor'
  } else if (value == 1) {
    return 'Legal'
  } else if (value == 2) {
    return 'Cuidador'
  }
}

Vue.filter('tipoResponsavelString', tipoResponsavelString)

const alergiaString = function (value) {
  if (value == 0) {
    return 'Não'
  } else if (value == 1) {
    return 'Sim'
  }
}

Vue.filter('alergiaString', alergiaString)

const hipersensibilidadeString = function (value) {
  if (value == 0) {
    return 'Não'
  } else if (value == 1) {
    return 'Sim'
  }
}

Vue.filter('hipersensibilidadeString', hipersensibilidadeString)

Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  return value.charAt(0).toUpperCase() + value.slice(1)
})

Vue.filter('formatDateToBR', function (value) {
  if (value) {
    return moment(String(value)).format('DD/MM/YYYY')
  }
})

Vue.filter('formatDateTimeToBR', function (value) {
  if (value) {
    return moment(String(value)).format('DD/MM/YYYY H:mm:ss')
  }
})

Vue.filter('formatBRtoDate', function (value) {
  if (value) {
    return moment(String(value), 'DD/MM/YYYY').format('YYYY-MM-DD')
  }
})

Vue.filter('byteTransform', function (value, to) {
  const constTerm = 1024

  if (to === 'KB') {
    return (value / constTerm).toFixed(3) + 'KB'
  } else if (to === 'MB') {
    return (value / constTerm ** 2).toFixed(3) + 'MB'
  } else if (to === 'GB') {
    return (value / constTerm ** 3).toFixed(3) + 'GB'
  } else if (to === 'TB') {
    return (value / constTerm ** 4).toFixed(3) + 'TB'
  } else {
    return 'Insira uma opção válida'
  }
})

Vue.filter('phone', function (phone) {
  if (phone == '' || phone == null) {
    return phone
  } else {
    return phone.replace(/[^0-9]/g, '').replace(/(\d{2})(\d{2})(\d{5})(\d{4})/, '+$1 ($2) $3-$4')
  }
})

Vue.filter('cnpj', function (cnpj) {
  if (cnpj == '' || cnpj == null) {
    return cnpj
  } else {
    return cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5')
  }
})

Vue.filter('cnpj', function (cnpj) {
  if (cnpj == '' || cnpj == null) {
    return cnpj
  } else {
    return cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5')
  }
})

const abreviarString = function (value) {
  if (value) {
    if (value == null) {
      return ''
    }
    let tam = value.length
    if (tam > 25) {
      let rest = value.substring(0, 25)
      return rest + '...'
    } else {
      return value
    }
  }
}

Vue.filter('abreviarString', abreviarString)
