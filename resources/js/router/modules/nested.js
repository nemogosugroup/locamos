/** When your routing table is too long, you can split it into small modules **/

import Layout from '@/layout'
import Menu1 from '@/views/nested/menu1/index'
import Menu2 from '@/views/nested/menu2/index'
import Menu1_1 from '@/views/nested/menu1/menu1-1'
import Menu1_2_1 from '@/views/nested/menu1/menu1-2/menu1-2-1'
import Menu1_2_2 from '@/views/nested/menu1/menu1-2/menu1-2-2'
import Menu1_2 from '@/views/nested/menu1/menu1-2'
import Menu1_3 from '@/views/nested/menu1/menu1-3'

const nestedRouter = {
  path: '/nested',
  component: Layout,
  redirect: '/nested/menu1/menu1-1',
  name: 'Nested',
  meta: {
    title: 'Nested Routes',
    icon: 'ri-menu-unfold-fill'
  },
  children: [
    {
      path: 'menu1',
      component: Menu1, // Parent router-view
      name: 'Menu1',
      meta: { title: 'Menu 1' },
      redirect: '/nested/menu1/menu1-1',
      children: [
        {
          path: 'menu1-1',
          component: Menu1_1,
          name: 'Menu1-1',
          meta: { title: 'Menu 1-1' }
        },
        {
          path: 'menu1-2',
          component: Menu1_2,
          name: 'Menu1-2',
          redirect: '/nested/menu1/menu1-2/menu1-2-1',
          meta: { title: 'Menu 1-2' },
          children: [
            {
              path: 'menu1-2-1',
              component: Menu1_2_1,
              name: 'Menu1-2-1',
              meta: { title: 'Menu 1-2-1' }
            },
            {
              path: 'menu1-2-2',
              component: Menu1_2_2,
              name: 'Menu1-2-2',
              meta: { title: 'Menu 1-2-2' }
            }
          ]
        },
        {
          path: 'menu1-3',
          component: Menu1_3,
          name: 'Menu1-3',
          meta: { title: 'Menu 1-3' }
        }
      ]
    },
    {
      path: 'menu2',
      name: 'Menu2',
      component: Menu2,
      meta: { title: 'Menu 2' }
    }
  ]
}

export default nestedRouter
