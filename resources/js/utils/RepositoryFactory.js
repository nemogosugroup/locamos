import UserRepository from '@/api/user/index';
import RoleRepository from '@/api/role';
import ProjectRepository from '@/api/project';
import langRepository from '@/api/lang/index';
import mapRepository from '@/api/map/index';
const repositories = {
	user: UserRepository,
	role: RoleRepository,
	project: ProjectRepository,
	lang: langRepository,
	map: mapRepository,
}
const RepositoryFactory = {
	get: name => repositories[name]
}
export default RepositoryFactory