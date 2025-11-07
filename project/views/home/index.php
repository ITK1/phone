<div class="hero-section text-center text-white mb-5">
    <div class="container">
        <h1 class="display-4 fw-bolder text-uppercase">Dịch Vụ Cày Thuê Uy Tín Số #1</h1>
        <p class="lead mb-4">An toàn tuyệt đối - Nhanh chóng - Bảo mật thông tin khách hàng</p>
        <a href="#services" class="btn btn-glow btn-lg"><i class="fas fa-bolt me-2"></i>XEM GÓI NGAY</a>
    </div>
</div>

<div class="container pb-5" id="services">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="text-uppercase fw-bold" style="color: var(--secondary-color);">
                <i class="fas fa-fire me-2"></i>Các Gói Dịch Vụ HOT
            </h2>
            <p class="text-muted">Chọn gói phù hợp với nhu cầu của bạn</p>
        </div>
    </div>

    <div class="row g-4">
        <?php if (isset($services) && is_array($services)): ?>
            <?php foreach ($services as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card service-card h-100">
                        <img src="<?= !empty($item['image']) ? htmlspecialchars($item['image']) : 'https://via.placeholder.com/400x200?text=No+Image' ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($item['name']) ?>"
                             onerror="this.src='https://i.imgur.com/P8Mh7I3.png'"> <div class="card-body d-flex flex-column text-white">
                            <h4 class="card-title fw-bold"><?= htmlspecialchars($item['name']) ?></h4>
                            <p class="card-text text-white-50 flex-grow-1">
                                <?= htmlspecialchars($item['description']) ?>
                            </p>
                            <hr style="border-color: var(--primary-color);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="price-tag">
                                    <?= number_format($item['price'], 0, ',', '.') ?>đ
                                </div>
                                <a href="index.php?page=order&id=<?= $item['id'] ?>" class="btn btn-glow btn-sm">
                                    Thuê Ngay <i class="fas fa-angle-double-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center w-100">Không có dịch vụ nào để hiển thị.</div>
        <?php endif; ?>
    </div>
</div>