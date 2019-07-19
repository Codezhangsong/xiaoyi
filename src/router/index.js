import Vue from 'vue'
import Router from 'vue-router'
import Meta from 'vue-meta'

import globals from '@/globals'

import dashboardsRoutes from './dashboards'


import uiRoutes from './ui'

import tablesRoutes from './tables'
import iconsRoutes from './icons'

import loginRoutes from './login'

import parentsRoutes from './parents'
import studentsRoutes from './students'
import messagesRoutes from './messages'
import wxarticlesRoutes from './wxarticles'
import home from './home'
import zbkc from './zb'
import mechanism from './mechanism'
import yygl from './yygl'
import system from './system'
import membersRoutes from './members'
import org from './org'

Vue.use(Router)
Vue.use(Meta)

const ROUTES = [
  // Default  route
  //   { path: '', redirect: '/dashboards/dashboard-2' },
   /*  { path: '', redirect: '/home/home' }*/
  { path: '', redirect: '/login/login' }
]

  .concat(home)
  .concat(zbkc)
  .concat(mechanism)
  .concat(yygl)
  .concat(system)
  .concat(dashboardsRoutes)
  .concat(uiRoutes)
  .concat(tablesRoutes)
  .concat(iconsRoutes)
  .concat(loginRoutes)
  .concat(parentsRoutes)
  .concat(studentsRoutes)
  .concat(messagesRoutes)
  .concat(wxarticlesRoutes)
  .concat(membersRoutes)
  .concat(org)

const router = new Router({
  base: '/',
  mode: 'history',
  routes: ROUTES
})

router.afterEach(() => {
  // Remove initial splash screen
  const splashScreen = document.querySelector('.app-splash-screen')
  if (splashScreen) {
    splashScreen.style.opacity = 0
    setTimeout(() => splashScreen && splashScreen.parentNode.removeChild(splashScreen), 300)
  }

  // On small screens collapse sidenav
  if (window.layoutHelpers && window.layoutHelpers.isSmallScreen() && !window.layoutHelpers.isCollapsed()) {
    setTimeout(() => window.layoutHelpers.setCollapsed(true, true), 10)
  }

  // Scroll to top of the page
  globals().scrollTop(0, 0)
})

router.beforeEach((to, from, next) => {
  // Set loading state

  document.body.classList.add('app-loading')

  /* document.body.reload()*/

  // Add tiny timeout to finish page transition
  setTimeout(() => next(), 100)
})

export default router
