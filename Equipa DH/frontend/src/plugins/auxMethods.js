import SessionStorage from '@/services/session-storage'

export default {
  install (Vue, options) {
    Vue.prototype.$validDate = function (val, futureProof = false) {
      if (val === null) {
        return true
      }

      let date = this.$moment(val, 'YYYY-MM-DD', true)
      if (!date.isValid()) {
        return false
      }

      let minDate = this.$moment('1900-01-01', 'YYYY-MM-DD')
      let maxDate = this.$moment()

      return date.isBetween(minDate, maxDate, null, '[]')
    }

    Vue.prototype.$validCPF = function (cpf = '') {
      if (cpf === null) {
        return true
      }

      cpf = cpf.replace(/\D/g, '')

      if (cpf.toString().length !== 11 || /^(\d)\1{10}$/.test(cpf)) { return false }
      let sum = 0
      let rev

      for (let i = 1; i <= 9; i++) {
        sum = sum + parseInt(cpf.substring(i - 1, i)) * (11 - i)
      }

      rev = (sum * 10) % 11
      if ((rev === 10) || (rev === 11)) { rev = 0 }
      if (rev !== parseInt(cpf.substring(9, 10))) { return false }

      sum = 0
      for (let i = 1; i <= 10; i++) {
        sum = sum + parseInt(cpf.substring(i - 1, i)) * (12 - i)
      }

      rev = (sum * 10) % 11
      if ((rev === 10) || (rev === 11)) { rev = 0 }
      if (rev !== parseInt(cpf.substring(10, 11))) { return false }

      return true
    }

    Vue.prototype.$validCNPJ = function (cnpj = '') {
      cnpj = cnpj.replace(/[^\d]+/g, '')

      if (cnpj === '') return false

      if (cnpj.length !== 14) return false

      let size = cnpj.length - 2
      let numbers = cnpj.substring(0, size)
      let digits = cnpj.substring(size)
      let sum = 0
      let pos = size - 7

      for (let i = size; i >= 1; i--) {
        sum += numbers.charAt(size - i) * pos--
        if (pos < 2) pos = 9
      }

      let result = sum % 11 < 2 ? 0 : 11 - sum % 11
      if (result !== Number(digits.charAt(0))) return false

      size = size + 1
      numbers = cnpj.substring(0, size)
      sum = 0
      pos = size - 7

      for (let i = size; i >= 1; i--) {
        sum += numbers.charAt(size - i) * pos--
        if (pos < 2) pos = 9
      }

      result = sum % 11 < 2 ? 0 : 11 - sum % 11
      if (result !== Number(digits.charAt(1))) return false

      return true
    }

    Vue.prototype.$validMimeType = function (fileType, mimeTypes) {
      if (fileType === null) {
        return false
      }
      return mimeTypes.includes(fileType)
    }

    Vue.prototype.$abrirDocumento = function (tipo, id) {
      let token = SessionStorage.get('token')
      window.open(`${process.env.ROOT_API1}api/download/${tipo}/${id}?token=${token}`, '_blank')
    }
  }
}
