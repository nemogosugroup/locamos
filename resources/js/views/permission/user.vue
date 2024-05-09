<template>
    <div class="app-container">
        <div class="filter-container f-left">
            <el-input
                placeholder="Tên"
                v-model="listQuery.fullname"
                style="width: 200px"
                class="filter-item"
            />
            <el-input
                placeholder="Email"
                v-model="listQuery.email"
                style="width: 200px"
                class="filter-item"
            />
            <el-button
                class="filter-item"
                type="primary"
                icon="el-icon-search"
                @click="handleSearch"
            >
                Tìm kiếm
            </el-button>
            <el-button
                class="filter-item"
                type="primary"
                icon="el-icon-remove-outline"
                @click="handleClearSearch"
            >
                Làm mới
            </el-button>
            <!-- <el-button
                class="filter-item"
                type="primary"
                icon="el-icon-edit"
                @click="handleCreate"
            >
                Thêm
            </el-button> -->
            <el-button class="filter-item" type="primary" icon="el-icon-download" v-bind:loading="isLoadingExportExcel" @click="handleExport">
                Export Excel
            </el-button>
        </div>
        <el-table
            ref="dragTable"
            v-loading="isLoading"
            v-bind:data="
                list.filter(
                    (data) =>
                        !search ||
                        data.title.toLowerCase().includes(search.toLowerCase())
                )
            "
            row-key="id"
            border
            fit
            highlight-current-row
            style="width: 100%"
        >
            <!-- Họ tên -->
            <el-table-column min-width="80px" label="Họ tên">
                <template v-slot="item">
                    <span>{{
                        item.row.first_name + " " + item.row.last_name
                    }}</span>
                </template>
            </el-table-column>
            <!-- Name -->
            <!-- <el-table-column label="Tên tài khoản" align="center" min-width="60%">
                <template v-slot="item">
                    <span class="link-type" @click="handleEdit(item.$index, item.row)">{{ item.row.username }}</span>
                </template>
            </el-table-column> -->
            <!-- Email -->
            <el-table-column min-width="80px" label="Email">
                <template v-slot="item">
                    <span>{{ item.row.email }}</span>
                </template>
            </el-table-column>

            <!-- Action -->
            <el-table-column align="center" min-width="50px" label="Sửa">
                <template v-slot="item">
					<!-- Nút sửa -->
					<el-button  type="primary" size="small" @click="handleEdit(item.row)">Sửa</el-button>
                </template>
            </el-table-column>
        </el-table>
        <!-- Phân trang -->
        <pagination 
            v-show="total>10" 
            v-bind:total="total" 
            v-model:page="listQuery.page" 
            v-model:limit="listQuery.limit"  
            @pagination="fetch"
        />
		 <!-- Dialog Update -->
		 <el-dialog :title="titleDialog" v-model="dialogFormVisible">
            <el-form ref="postForm" :model="postForm" v-bind:rules="rules" label-width="160px">
                <el-form-item label="Email" prop="email">
                    <el-input v-model="postForm.email" :disabled="dialogUser"></el-input>
                </el-form-item>

                <el-form-item label="Họ chữ đệm" prop="first_name">
                    <el-input v-model="postForm.first_name"></el-input>
                </el-form-item>

                <el-form-item label="Tên" prop="last_name">
                    <el-input v-model="postForm.last_name"></el-input>
                </el-form-item>
                <el-form-item label="Thay đổi mật khẩu" style="width: 200px;" v-show="showChangePass">
                    <el-switch
                        v-model="postForm.showPass"
                        class="ml-2"
                        inline-prompt
                        @change="handleChangePass"
                    />
                </el-form-item>
                <el-form-item v-show="showEditPass" label="Mật khẩu" prop="password">
                    <el-input
                        v-model="postForm.password"
                        type="password"
                        placeholder="Vui lòng nhập mật khẩu"
                        show-password
                    />
                </el-form-item>

                <el-form-item v-show="showEditPass" label="Nhập lại mật khẩu" prop="confirm_password">
                    <el-input
                        v-model="postForm.confirm_password"
                        type="password"
                        placeholder="Vui lòng xác nhận mật khẩu"
                        show-password
                    />
                </el-form-item>

                <el-form-item label="Quyền hạn" prop="roles">
                    <el-select filterable placeholder="Quyền hạn"  style="width:100%;" v-model="postForm.roles" multiple>
                        <el-option v-for="item in listRoles" :key="item.key" :label="item.name" :value="item.key" />
                    </el-select>
                </el-form-item>
            </el-form>
            <template v-slot:footer>
                <div class="dialog-footer">
                    <el-button @click="dialogFormVisible = false">
                        Hủy
                    </el-button>
                    <el-button type="primary" @click="saveForm()" v-bind:loading="isLoadingSave">
                        Lưu
                    </el-button>
                </div>
            </template>
            
        </el-dialog>
    </div>
</template>

<script>
import Pagination from "@/components/Pagination";
import RepositoryFactory from "@/utils/RepositoryFactory";
import { mapGetters } from "vuex";
import moment from 'moment';
const UserRepository = RepositoryFactory.get("user");
const RoleRepository = RepositoryFactory.get("role");
export default {
    name: "ListUsers",
    components: { Pagination },
    // components: { UserCard, Activity, Timeline, Account },
    data() {
		var validatePass = (rule, value, callback) => {
			if (value === '') {
				callback(new Error('Vui lòng nhập xác nhận mật khẩu'));
			} else if (value !== this.postForm.password) {
				callback(new Error('Mật khẩu không trùng nhau!'));
			} else {
				callback();
			}
		};
		var validateEmail = (rule, value, callback) => {
			const emailRegex = /gosu\.vn$/;
			if (value === '') {
				callback(new Error('Vui lòng nhập địa chỉ email'));
			} else if (!emailRegex.test(value)) {
				callback(new Error('Định dạng email phải là xxx@gosu.vn'));
			} else {
				callback();
			}
		};
        return {
            isLoading: true,
            list: [],
            listRoles: [],
            total: 0,
            search: "",
            listQuery: {
                page: 1,
                limit: 10,
                fullname: "",
                email: "",
                status: "",
                ip: undefined,
            },
			isLoadingExportExcel: false,
			dialogFormVisible: false,
			isLoadingSave: false,
			dialogStatus: '',
			dialogIndex: undefined,
			dialogUser: true,// mở input user
			postForm: {
				//username: '',
				email: '',
				firstname:'',
				lastname:'',
				password:'',
				confirm_password:'',
				roles: [],
				//is_active: '',
				//is_block: '', 
				showPass: false,                  
			},
			showChangePass:false,
			showEditPass:false,
			titleDialog:'Tạo tài khoản',
			rules: {
				email: [
					{validator: validateEmail, trigger: 'blur'},
					{ type:'email', message: 'Địa chỉ email không chính xác', trigger: ['blur', 'change'] },
					{
						required: true,
						message: 'Vui lòng nhập địa chỉ email',
						trigger: 'blur',
					},
				],
				first_name: [
					{ required: true, message: 'Vui lòng nhập họ và chữ đệm', trigger: 'blur' },
				],
				last_name: [
					{ required: true, message: 'Vui lòng nhập tên', trigger: 'blur' },
				],
				password: [
					{required: true, message: 'Vui lòng nhập mật khẩu', trigger: 'blur'},
					{min: 6, message: 'Mật khẩu phải có từ 6 ký tự trở lên'},
				],
				confirm_password: [
					{required: true, message: 'Vui lòng nhập xác nhận mật khẩu'},
					{validator: validatePass, trigger: 'blur'},
					{min: 6, message:  'Mật khẩu phải có từ 6 ký tự trở lên'},
				],
			},
        };
    },
    computed: {
        ...mapGetters(["name", "avatar", "roles"]),
    },
    created() {
        this.fetch();
		this.getRoles();
    },
    methods: {
		async getRoles(){
			const { data } = await RoleRepository.list();
			this.listRoles = data.data;
		},

        async fetch() {
            this.isLoading = true;
            const { data } = await UserRepository.list(this.listQuery);
            this.list = data.data.data;
            this.total = data.data.total;
            this.isLoading = false;
        },
        handleCreate() {
            this.dialogUser = false;
            this.dialogStatus = "create";
            this.dialogFormVisible = true;
            this.postForm = {
                is_active: null,
                roles: null,
                email: "",
                first_name: "",
                last_name: "",
                password: "",
                confirm_password: "",
                showPass: true,
            };
            this.showEditPass = true;
            this.showChangePass = false;
            this.removeRequiredRules(true);
            this.titleDialog = "Tạo tài khoản";
        },

        handleEdit(item) {
            this.titleDialog = "Sửa tài khoản";
            this.dialogUser = true;
            this.dialogStatus = "edit";
            this.dialogFormVisible = true;
			let roles = [];
			item.roles.filter(i => roles.push(i.name));
            this.postForm = {
                email: item.email,
                first_name: item.first_name,
                last_name: item.last_name,
                roles: roles,
                showPass: false,
				id:item.id
            };
            this.showChangePass = true;
            this.removeRequiredRules(false);
            this.showEditPass = false;
        },

        //Save
        saveForm() {
            this.$refs["postForm"].validate((valid) => {
                if (valid) {
                    this.isLoadingSave = true;

                    if (this.dialogStatus == "edit") {
						let dataUpdate = {
							'first_name':this.postForm.first_name,
							'last_name':this.postForm.last_name,
						};
                        const query = {
                            field: dataUpdate,
                            id: this.postForm.id,
                            check: this.postForm.showPass,
							password: this.postForm.password,
							roles: this.postForm.roles,
                        };
						console.log('query.id', query.id)
                        UserRepository.update(query)
                            .then((response) => {
                                const { data } = response;
                                this.isLoadingSave = false;
                                if (data.success) {
                                    this.$message({
                                        message: data.message,
                                        type: "success",
                                    });
									this.list.filter(item => {
										if (item.id == query.id) {
											item = Object.assign(
												item,
												data.data
											);
										}
									})
                                } else {
                                    this.$message({
                                        message: data.message,
                                        type: "error",
                                    });
                                }

                                this.dialogFormVisible = false;
                            })
                            .catch((e) => {
                                this.isLoadingSave = false;
                            });
                    } else {
                        UserRepository.create(this.postForm)
                            .then((response) => {
                                this.isLoadingSave = false;
                                const { data } = response;
                                if (data.success) {
                                    this.$message({
                                        message: data.message,
                                        type: "success",
                                    });
                                    this.list.unshift(data.data);
                                } else {
                                    this.$message({
                                        message: data.message,
                                        type: "error",
                                    });
                                }

                                this.dialogFormVisible = false;
                            })
                            .catch((e) => {
                                this.isLoadingSave = false;
                            });
                    }
                } else {
                    console.log("error submit!!");
                    return false;
                }
            });
        },

        handleSearch() {
            this.listQuery.page = 1;
            this.fetch();
        },

        handleClearSearch() {
            this.listQuery = {
                page: 1,
                limit: 10,
                search: undefined,
                fullname: "",
                status: "",
                email: "",
            };
            this.fetch();
        },

        // export user
        async handleExport() {
            this.isLoadingExportExcel = true;
            const { data } = await UserRepository.export();
            var date = moment().format("YYYY-MM-DD");

            if (data.success) {
                var list = data.list;
                import("@/vendor/Export2Excel").then((excel) => {
                    const tHeader = ["Họ tên", "Email"];
                    const filterVal = ["first_name", "email"];
                    let data = this.formatJson(filterVal, list);
                    excel.export_json_to_excel({
                        header: tHeader,
                        data,
                        filename: "Users -" + date,
                        autoWidth: true,
                        bookType: "xlsx",
                    });
                });
            } else {
                this.$message({
                    message: data.message,
                    type: "error",
                });
            }
            this.isLoadingExportExcel = false;
        },
        formatJson(filterVal, jsonData) {
            return jsonData.map((v) =>
                filterVal.map((j) => {
                    if (j === "timestamp") {
                        return parseTime(v[j]);
                    } else {
                        return v[j];
                    }
                })
            );
        },
        // show hide change password
        handleChangePass(){
            if (this.postForm.showPass) {
                this.showEditPass = true
                this.removeRequiredRules(true);
            }else{
                this.showEditPass = false
                this.removeRequiredRules(false);
            }
        },
        removeRequiredRules(value){
            this.rules.password[0].required = value;
            this.rules.confirm_password[0].required = value;
        }
    },
};
</script>
