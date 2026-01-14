@php
    // Load thêm quan hệ thanhtoan để lấy trạng thái thanh toán của từng đơn
    $tours = \App\Models\Tour::whereHas('chuyentour.datCho.khachThamGia')
                 ->with([
                     'chuyentour.datCho.khachThamGia',
                     'chuyentour.datCho.thanhtoan' // <<< QUAN TRỌNG: để lấy trạng thái thanh toán
                 ])
                 ->get();
@endphp

@foreach($tours as $tour)
    @foreach($tour->chuyentour as $chuyen)
        @php
            $allKhach = $chuyen->datCho->pluck('khachThamGia')->flatten();

            // Sắp xếp theo maKhach để thứ tự ổn định
            $khachs = $allKhach->sortBy('maKhach')->values();
        @endphp

        <!-- Modal khách của chuyến -->
        <div class="modal fade" id="modalKhach{{ $chuyen->maChuyen }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- HEADER -->
                    <div class="modal-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="modal-title m-0">
                            Khách Tham Gia – Chuyến #00{{ $chuyen->maChuyen }}
                        </h5>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('admin.datcho.export.khachchuyen', $chuyen->maChuyen) }}"
                               class="btn btn-light btn-sm me-3" title="Xuất Excel">
                                <i class="fas fa-file-excel me-1"></i> Xuất Excel
                            </a>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body">
                        <p class="mb-3 text-muted">
                            <strong>Tour:</strong> {{ $tour->tieuDe }}<br>
                            <strong>Thời gian:</strong> 
                            {{ \Carbon\Carbon::parse($chuyen->ngayBatDau)->format('d/m/Y') }} → 
                            {{ \Carbon\Carbon::parse($chuyen->ngayKetThuc)->format('d/m/Y') }}
                        </p>

                        @if($khachs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="8%">STT</th>
                                            <th>Họ Tên</th>
                                            <th width="10%">Tuổi</th>
                                            <th width="12%">Giới Tính</th>
                                            <th width="15%">Phòng</th>
                                            <th width="12%">Mã Booking</th>
                                            <th width="15%">Trạng thái thanh toán</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($khachs as $index => $khach)
                                            @php
                                                $datChoCuaKhach = $chuyen->datCho->firstWhere('maDatCho', $khach->maDatCho);
                                                $trangThaiTT = $datChoCuaKhach?->thanhtoan?->tinhTrangThanhToan ?? 'Chưa thanh toán';
                                                $isDaThanhToan = $trangThaiTT === 'Đã thanh toán';
                                            @endphp
                                            <tr>
                                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                                <td class="fw-500">{{ $khach->hoTenKhach }}</td>
                                                <td>{{ $khach->tuoi }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $khach->gioiTinh == 'Nam' ? 'primary' : 'danger' }}">
                                                        {{ $khach->gioiTinh == 'Nam' ? 'Nam' : 'Nữ' }}
                                                    </span>
                                                </td>
                                                <td>{{ $khach->luaChonPhong == 'PhongDon' ? 'Phòng đơn' : 'Ghép phòng' }}</td>
                                                <td>
                                                    <a href="{{ route('admin.datcho.show', $khach->maDatCho) }}" class="text-decoration-none">
                                                        #{{ str_pad($khach->maDatCho, 6, '0', STR_PAD_LEFT) }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $isDaThanhToan ? 'bg-success' : 'bg-warning text-dark' }}">
                                                        {{ $trangThaiTT }}
                                                    </span>
                                                </td>
                                                <td class="fw-500">{{ $khach->maVe }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-3 fw-bold text-primary">
                                Tổng cộng: {{ $khachs->count() }} khách
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                <p>Chưa có khách đăng ký cho chuyến này.</p>
                            </div>
                        @endif
                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@endforeach