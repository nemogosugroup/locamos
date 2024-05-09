import UserRepository from '@/api/user/index';
import RoleRepository from '@/api/role';
import ProjectRepository from '@/api/project';
import MarketRepository from '@/api/market';
import QuestRepository from '@/api/quest';
import userEquipmentRepository from '@/api/equipment/index';
import langRepository from '@/api/lang/index';
const repositories = {
	user: UserRepository,
	role: RoleRepository,
	project: ProjectRepository,
	market: MarketRepository,
	quest: QuestRepository,
	userEquipment: userEquipmentRepository,
	lang: langRepository,
}
const RepositoryFactory = {
	get: name => repositories[name]
}
export default RepositoryFactory