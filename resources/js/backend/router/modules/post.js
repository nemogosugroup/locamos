import Layout from '@/layout'
import Post from '@/backend/views/post'
import Category from '@/backend/views/post/category'
import Import from '@/backend/views/post/import'
const postRouter = {
    path: '/admin/post',
    component: Layout,
    redirect: '/admin/post',
    name: 'Post',
    meta: {
        title: 'Post',
        icon: 'ri-git-repository-fill',
        roles: ['admin']
    },
    children: [
        {
            path: 'category',
            component: Category,
            name: 'Category',
            meta: { 
                title: 'Danh mục', 
                icon: 'ri-layout-grid-fill',
                roles: ['admin']
            }
        },
        {
            path: 'post',
            component: Post,
            name: 'Course',
            meta: { title: 'Post', icon: 'ri-git-repository-fill' }
        },
        {
            path: 'import',
            component: Import,
            name: 'Import',
            meta: { title: 'Import', icon: 'ri-file-excel-2-fill' }
        }
    ]
}
  
export default postRouter