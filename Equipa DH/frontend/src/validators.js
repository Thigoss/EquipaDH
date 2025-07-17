/* eslint-disable eqeqeq */

import moment from 'moment'

export function isNameJoe (value) {
  if (!value) return true
  return value === 'Joe'
}

export function notGmail (value = '') {
  return !value.includes('gmail')
}

export function isEmailAvailable (value) {
  if (value === '') return true

  return new Promise((resolve, reject) => {
    setTimeout(() => {
      resolve(value.length > 10)
    }, 500)
  })
}

export function isAntes (value = '', value2 = '') {
  return moment(value).isBefore(value2.dt_vigencia_final)
  // return moment(start).isBefore(end)
}

const validarCPF = (value, component) => {
  return isCPF(value)
}

function isCPF (value) {
  var cpf = value.replace(/\.|-/g, '')

  if (digitosIguais(cpf)) {
    return false
  }

  if (!validaPrimeiroDigito(cpf)) {
    return false
  }
  if (!validaSegundoDigito(cpf)) {
    return false
  }
  return true
}

function validaPrimeiroDigito (cpf) {
  let soma = 0
  for (var i = 0; i < cpf.length - 2; i++) {
    soma += cpf[i] * ((cpf.length - 1) - i)
  }
  soma = (soma * 10) % 11
  if (soma == 10 || soma == 11) {
    soma = 0
  }
  if (soma != cpf[9]) {
    return false
  }
  return true
}

function validaSegundoDigito (cpf) {
  let soma = 0
  for (var i = 0; i < cpf.length - 1; i++) {
    soma += cpf[i] * ((cpf.length) - i)
  }
  soma = (soma * 10) % 11
  if (soma == 10 || soma == 11) {
    soma = 0
  }
  if (soma != cpf[10]) {
    return false
  }
  return true
}

function digitosIguais (num) {
  var first = num % 10
  while (num) {
    if (num % 10 !== first) {
      return false
    }
    num = Math.floor(num / 10)
  }
  return true
}

const validarNascimento = (value, component) => {
  return nascimentoValido(value)
}

function nascimentoValido (data) {
  if (!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(data)) {
    return false
  }

  var todayYear = new Date().getFullYear()

  var parts = data.split('/')
  var day = parseInt(parts[0], 10)
  var month = parseInt(parts[1], 10)
  var year = parseInt(parts[2], 10)

  // Check the ranges of month and year
  if (year < 1900 || year > todayYear || month == 0 || month > 12) {
    return false
  }

  var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]

  // Adjust for leap years
  if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0)) {
    monthLength[1] = 29
  }

  // Check the range of the day
  return day > 0 && day <= monthLength[month - 1]
}

const validarExtensao = (value, component) => {
  return extensaoValida(value)
}

function extensaoValida (file) {
  if (file === null) {
    return true
  }
  var extensao = file.name.split('.')
  if (extensao === 'exe') {
    return true
  }
  return false
}

const validarEmail = (value, component) => {
  return emailValida(value)
}

function emailValida (email) {
  let re = /^(([^<>()\\[\]\\.,;:\s@"]+(\.[^<>()\\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  return re.test(email)
}

export {
  validarCPF,
  validarNascimento,
  validarExtensao,
  validarEmail
}
