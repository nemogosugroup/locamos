import MedalAdminRepository from '@/backend/api/medal'
import CategoryMedalRepository from '@/backend/api/medal/category'
import MissionAdminRepository from '@/backend/api/mission'
import CategoryMissionAdminRepository from '@/backend/api/mission/category'
import SubCategoryMissionAdminRepository from '@/backend/api/mission/subCategory'
import levelAdminRepository from '@/backend/api/level'
import CategoryEquipmentRepository from '@/backend/api/equipment/category'
import TypeEquipmentRepository from '@/backend/api/equipment/type'
import EquipmentRepository from '@/backend/api/equipment'
import PostRepository from '@/backend/api/post'
import CategoryPostRepository from '@/backend/api/post/category'
const repositories = {
	medal: MedalAdminRepository,
	categoryMedal: CategoryMedalRepository,
	mission: MissionAdminRepository,
	categoryMission: CategoryMissionAdminRepository,
	subCategoryMission: SubCategoryMissionAdminRepository,
	level: levelAdminRepository,
	categoryEquipment: CategoryEquipmentRepository,
	typeEquipment: TypeEquipmentRepository,
	equipment: EquipmentRepository,
	categoryPost: CategoryPostRepository,
	post: PostRepository,
}
const AdminRepositoryFactory = {
	get: name => repositories[name]
}
export default AdminRepositoryFactory