<style>
    /* CSS riêng cho form để không bị xung đột */
    .order-form-card {
        background-color: #1a1a2e; /* Nền card tối */
        border: 1px solid #6f42c1;
        box-shadow: 0 0 20px rgba(111, 66, 193, 0.2);
    }
    .form-label {
        color: #0dcaf0; /* Màu nhãn xanh neon */
        font-weight: bold;
    }
    .form-control {
        background-color: #0f0f1a !important; /* Nền ô nhập tối hơn */
        border: 1px solid #6f42c1 !important; /* Viền tím */
        color: #fff !important; /* Chữ màu trắng */
    }
    .form-control:focus {
        background-color: #0f0f1a !important;
        border-color: #0dcaf0 !important; /* Khi bấm vào viền chuyển xanh */
        box-shadow: 0 0 10px rgba(13, 202, 240, 0.5) !important;
        color: #fff !important;
    }
    /* Màu placeholder (chữ mờ gợi ý) */
    .form-control::placeholder {
        color: #6c757d !important;
        opacity: 0.8;
    }
</style>

<div class="container my-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card order-form-card rounded-4 overflow-hidden">
                <div class="card-header text-white p-4" style="background: linear-gradient(45deg, #6f42c1, #8a2be2);">
                    <h4 class="mb-0 fw-bold text-uppercase">
                        <i class="fas fa-scroll me-2"></i> Xác Nhận Đơn Hàng
                    </h4>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="alert mb-4 d-flex align-items-center" style="background: rgba(13, 202, 240, 0.1); border-left: 5px solid #0dcaf0; color: #fff;">
                        <i class="fas fa-info-circle fa-2x me-3" style="color: #0dcaf0;"></i>
                        <div>
                            Bạn đang chọn gói: <strong class="text-uppercase" style="color: #0dcaf0;"><?= htmlspecialchars($service->name) ?></strong>
                            <div class="fs-4 fw-bold" style="color: #ffc107;">
                                <?= number_format($service->price, 0, ',', '.') ?> VNĐ
                            </div>
                        </div>
                    </div>

                    <form action="index.php?page=order_store" method="POST">
                        <input type="hidden" name="service_id" value="<?= $service->id ?>">
                        
                        <div class="mb-4">
                            <label for="fullname" class="form-label"><i class="fas fa-user me-2"></i>Họ tên của bạn <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="fullname" name="fullname" required placeholder="VD: Nguyễn Văn A">
                        </div>
                        
                        <div class="mb-4">
                            <label for="contact" class="form-label"><i class="fas fa-phone-alt me-2"></i>Liên hệ (Zalo/SĐT) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="contact" name="contact" required placeholder="098xxxx... để Admin gọi xác nhận">
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label" style="color: #ffc107;"><i class="fas fa-gamepad me-2"></i>Tài khoản game <span class="text-danger">*</span></label>
                                <input type="text" name="acc_username" class="form-control form-control-lg" required placeholder="Email hoặc Tên đăng nhập">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: #ffc107;"><i class="fas fa-key me-2"></i>Mật khẩu game <span class="text-danger">*</span></label>
                                <input type="text" name="acc_password" class="form-control form-control-lg" required placeholder="Nhập chính xác mật khẩu">
                            </div>
                            <div class="form-text mt-2" style="color: #0dcaf0;">
                                <i class="fas fa-shield-alt me-1"></i> Cam kết bảo mật thông tin tuyệt đối 100%.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label"><i class="fas fa-comment-dots me-2"></i>Ghi chú thêm</label>
                            <textarea class="form-control" id="message" name="message" rows="3" placeholder="Ví dụ: Chỉ cày vào buổi tối..."></textarea>
                        </div>

                        <div class="d-grid pt-3">
                            <button type="submit" class="btn btn-glow btn-lg text-uppercase fw-bold py-3" style="font-size: 1.2rem;">
                                <i class="fas fa-paper-plane me-2"></i> Gửi Đơn Hàng Ngay
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>