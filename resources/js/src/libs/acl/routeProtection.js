import ability from './ability'

export const canNavigate = (to, isLoggedIn) => to.matched.some(route => {
  const pathname = to.path
  const { rules } = ability
  const routeOption = {
    action: route.meta.action,
    subject: route.meta.resource,
  }

  if (isLoggedIn && routeOption.subject === 'ROLE_USER') {
    return true
  }

  if ((!routeOption.subject && !pathname.startsWith('/admin')
    && !pathname.startsWith('/candidate')
    && !pathname.startsWith('/company')) || routeOption.subject === 'Auth') {
    return true
  }

  return routeOption.subject === rules[0].subject
})

export const _ = undefined
