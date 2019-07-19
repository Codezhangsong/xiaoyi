import Layout2 from '@/layout/Layout2'

export default [{
  path: '/home',
  component: Layout2,
  children: [{
    path: 'home',
    component: () => import('@/components/home/home')
  }]
}]
