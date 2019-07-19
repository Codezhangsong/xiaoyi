import Layout2 from '@/layout/Layout2'

export default [{
  path: '/org',
  component: Layout2,
  children: [{
    path: 'list',
    component: () => import('@/components/org/List')
  },
  {
    path: 'edit',
    component: () => import('@/components/org/Edit')
  }]
}]
