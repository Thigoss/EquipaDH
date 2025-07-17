let timeoutId
let intervalId
let lastReset = Date.now()
const sessionDuration = 3600000

export function startSessionTimer () {
  clearInterval(intervalId)
  clearTimeout(timeoutId)

  intervalId = setInterval(() => {
    const now = Date.now()
    if (now - lastReset >= sessionDuration) {
      finishSession()
    }
  }, 60000)

  timeoutId = setTimeout(() => {
    finishSession()
  }, sessionDuration)
}

export function resetSessionTimer () {
  const now = Date.now()

  if (now - lastReset > 3000) {
    clearTimeout(timeoutId)
    startSessionTimer()
    lastReset = now
  }
}

function handleActivity () {
  resetSessionTimer()
}

export function addSessionTimerEvents () {
  window.addEventListener('mousemove', handleActivity, { passive: true })
  window.addEventListener('keypress', handleActivity, { passive: true })
}

export function removeSessionTimerEvents () {
  window.removeEventListener('mousemove', handleActivity)
  window.removeEventListener('keypress', handleActivity)
}

export function finishSession () {
  sessionStorage.clear()
  removeSessionTimerEvents()
  window.location.href = '/login'
}
