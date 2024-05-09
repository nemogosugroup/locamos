<?php 
namespace App\Helpers;

class Message {
    public function updateSuccess(){
        return 'Cập nhập dữ liệu thành công!';
    }
    public function createSuccess(){
        return 'Tạo dữ liệu thành công!';
    }
    public function deleteSuccess(){
        return 'Xoá dữ liệu thành công!';
    }
    public function updateError(){
        return 'Cập nhập dữ liệu thất bại!';
    }
    public function createError(){
        return 'Tạo dữ liệu thất bại!';
    }
    public function deleteError(){
        return 'Xoá dữ liệu thất bại!';
    }
    public function getSuccess(){
        return 'Lấy dữ liệu thành công!';
    }
    public function getError(){
        return 'Lấy dữ liệu thất bại!';
    }

    public function loginError(){
        return 'Tài khoản hoặc mật khẩu không đúng!';
    }
    public function emaiError(){
        return 'Địa chỉ email không chính xác!';
    }
    public function passError(){
        return 'Mật khẩu không chính xác!';
    }

    public function loginSuccess(){
        return 'Đăng nhập thành công!';
    }
    public function logoutSuccess(){
        return 'Đăng xuất thành công!';
    }
    public function dataExisted(){
        return 'Dữ liệu đã tồn tại!';
    }
    public function buyCourseSuccess(){
        return 'Bạn đã đổi khoá học thành công!';
    }
    public function buyCourseError(){
        return 'Bạn đổi khoá học không thành công!';
    }
    public function buyEquipmentSuccess(){
        return 'Bạn đã đổi trang bị thành công!';
    }
    public function buyEquipmentError(){
        return 'Bạn đổi trang bị không thành công!';
    }
    public function levelExist(){
        return 'Level đã tồn tại!';
    }
}