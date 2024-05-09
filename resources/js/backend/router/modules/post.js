import Layout from '@/layout'
import Post from '@/backend/views/post'
import Category from '@/backend/views/post/category'
const postRouter = {
    path: '/admin/post',
    component: Layout,
    redirect: '/admin/post',
    name: 'Post',
    meta: {
        title: 'Post',
        icon: 'ri-git-repository-fill'
    },
    children: [
        {
            path: 'category',
            component: Category,
            name: 'Category',
            meta: { title: 'Danh mục', icon: 'ri-layout-grid-fill'}
        },
        {
            path: 'post',
            component: Post,
            name: 'Course',
            meta: { title: 'Post', icon: 'ri-git-repository-fill' }
        }
    ]
}
  
export default postRouter