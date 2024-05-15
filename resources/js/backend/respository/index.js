import PostRepository from '@/backend/api/post'
import CategoryPostRepository from '@/backend/api/post/category'
import ImportRepository from '@/backend/api/post/import'
const repositories = {
	categoryPost: CategoryPostRepository,
	post: PostRepository,
	import: ImportRepository,
}
const AdminRepositoryFactory = {
	get: name => repositories[name]
}
export default AdminRepositoryFactory