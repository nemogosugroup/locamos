/** When your routing table is too long, you can split it into small modules **/

import Layout from '@/layout'
import DynamicTable from '@/views/table/dynamic-table/index'
import DragTable from '@/views/table/drag-table'
import InlineEditTable from '@/views/table/inline-edit-table'
import ComplexTable from '@/views/table/complex-table'

const tableRouter = {
  path: '/table',
  component: Layout,
  redirect: '/table/complex-table',
  name: 'Table',
  meta: {
    title: 'Table',
    icon: 'ri-table-fill'
  },
  children: [
    {
      path: 'dynamic-table',
      component: DynamicTable,
      name: 'DynamicTable',
      meta: { title: 'Dynamic Table' }
    },
    {
      path: 'drag-table',
      component: DragTable,
      name: 'DragTable',
      meta: { title: 'Drag Table' }
    },
    {
      path: 'inline-edit-table',
      component: InlineEditTable,
      name: 'InlineEditTable',
      meta: { title: 'Inline Edit' }
    },
    {
      path: 'complex-table',
      component: ComplexTable,
      name: 'ComplexTable',
      meta: { title: 'Complex Table' }
    }
  ]
}
export default tableRouter
