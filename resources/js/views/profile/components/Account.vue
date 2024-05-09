<template>
    <el-form label-width="150px">
        <el-form-item label="Họ tên">
            <el-input v-model.trim="user.fullname" disabled/>
        </el-form-item>
        <el-form-item label="Email">
            <el-input v-model.trim="user.email" disabled/>
        </el-form-item>
        <el-form-item label="Giới tính">
            <el-input v-model.trim="user.gender" disabled/>
        </el-form-item>
        <el-form-item label="Ngày sinh">
            <el-input v-model.trim="user.birthday" disabled/>
        </el-form-item>
        <el-form-item label="Bộ Phận/TT">
            <el-input v-model.trim="user.department" disabled/>
        </el-form-item>
        <el-form-item label="Vị trí công việc">
            <el-input v-model.trim="user.job" disabled/>
        </el-form-item>
        <el-form-item label="Nơi làm việc">
            <el-input v-model.trim="user.area" disabled/>
        </el-form-item>
        <el-form-item label="Số điện thoại">
            <el-input v-model.trim="user.phone" disabled/>
        </el-form-item>
        <el-form-item label="Thành tích">
            <el-select v-model="valMedalCategory" class="m-2" placeholder="chọn Thành Tích">
                <el-option
                    v-for="(item, idx) in dataMedal.medal_categories"
                    :key="idx"
                    :label="item.title"
                    :value="item.id"
                />
            </el-select>
        </el-form-item>
        <el-form-item label="Danh hiệu">
            <el-select v-model="valMedal" class="m-2" placeholder="chọn Danh Hiệu">
                <el-option
                    v-for="(item, idx) in dataMedal.medals"
                    :key="idx"
                    :label="item.title"
                    :value="item.id"
                />
            </el-select>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="submit">Update</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
export default {
    props: {
        user: {
            type: Object,
            default: {},
        },
        dataMedal: {
            type: Object,
            default: {},
        },
    },
    data() {
        return {
            valMedalCategory: this.user.achievements?.medal_category,
            valMedal: this.user.achievements?.medal,
        }
    },
    methods: {
        submit() {
            this.emitter.emit("is-update-user", {
                medal_category: this.valMedalCategory,
                medal: this.valMedal
            });
        },
    }
};
</script>
