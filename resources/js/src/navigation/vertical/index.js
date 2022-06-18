/*

Array of object

Top level object can be:
1. Header
2. Group (Group can have navItems as children)
3. navItem

* Supported Options

/--- Header ---/

header

/--- nav Grp ---/

title
icon (if it's on top level)
tag
tagVariant
children

/--- nav Item ---/

icon (if it's on top level)
title
route: [route_obj/route_name] (I have to resolve name somehow from the route obj)
tag
tagVariant

*/
import dashboard from './dashboard'
import account from './account'
import blog from './blog'
import news from './news'
import manage from './manage'
import project from './project'

// Array of sections
export default [
  ...dashboard,
  ...account,
  ...manage,
  ...project,
  ...news,
  ...blog,
]
