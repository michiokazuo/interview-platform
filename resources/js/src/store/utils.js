export default {
  updateUser(userData) {
    // eslint-disable-next-line no-param-reassign
    userData.ability = [
      {
        action: 'manage',
        subject: userData.role,
      },
    ]
    localStorage.setItem('userData', JSON.stringify(userData))
  },
  limitContent(description) {
    const parse = new DOMParser()
    const doc = parse.parseFromString(description, 'text/html')
    doc.body.querySelectorAll('img').forEach(img => {
      img.remove()
    })
    doc.body.querySelectorAll('pre').forEach(pre => {
      pre.remove()
    })
    doc.body.querySelectorAll('aside').forEach(aside => {
      aside.remove()
    })
    doc.body.querySelectorAll('blockquote').forEach(blockquote => {
      blockquote.remove()
    })

    return doc.body.innerHTML
  },
}
