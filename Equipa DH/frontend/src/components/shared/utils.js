import Vue from 'vue'
import moment from 'moment'

export function random (min, max) {
  return Math.floor(Math.random() * (max - min + 1) + min)
}

/**
 * Randomize array element order in-place.
 * Using Durstenfeld shuffle algorithm.
 */
export const shuffleArray = (array) => {
  for (let i = array.length - 1; i > 0; i--) {
    let j = Math.floor(Math.random() * (i + 1))
    let temp = array[i]
    array[i] = array[j]
    array[j] = temp
  }
  return array
}

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
