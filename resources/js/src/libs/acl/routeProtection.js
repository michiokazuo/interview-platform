import ability from './ability'

export const canNavigate = to => to.matched.some(route => {
  const pathname = to.path
  const { rules } = ability
  const routeOption = {
    action: route.meta.action,
    subject: route.meta.resource,
  }

  console.log(pathname)
  console.log(rules)
  console.log(routeOption)

  if ((!routeOption.subject && !pathname.startsWith('/admin')
    && !pathname.startsWith('/candidate')
    && !pathname.startsWith('/company')) || routeOption.subject === 'Auth') {
    return true
  }

  return routeOption.subject === rules[0].subject
})

export const _ = undefined
