import PostRepository from '@/backend/api/post'
import CategoryPostRepository from '@/backend/api/post/category'
const repositories = {
	categoryPost: CategoryPostRepository,
	post: PostRepository,
}
const AdminRepositoryFactory = {
	get: name => repositories[name]
}
export default AdminRepositoryFactory