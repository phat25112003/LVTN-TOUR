@php
    $tours = \App\Models\Tour::whereHas('chuyentour')
                 ->with(['chuyentour' => fn($q) => $q->orderBy('ngayBatDau')])
                 ->get();
@endphp

<!-- Modal danh sách chuyến tour -->
<div class="modal fade" id="modalDanhSachChuyen" tabindex="-1" aria-labelledby="modalDanhSachChuyenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDanhSachChuyenLabel">Danh Sách Các Chuyến Tour</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($tours->count() > 0)
                    <div class="accordion" id="accordionTours">
                        @foreach($tours as $tour)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $tour->maTour }}">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $tour->maTour }}">
                                        {{ $tour->tieuDe }} 
                                        <span class="badge bg-light text-dark ms-2">{{ $tour->chuyentour->count() }} chuyến</span>
                                    </button>
                                </h2>
                                <div id="collapse{{ $tour->maTour }}" class="accordion-collapse collapse"
                                     data-bs-parent="#accordionTours">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Mã Chuyến</th>
                                                        <th>Khởi Hành</th>
                                                        <th>Kết Thúc</th>
                                                        <th>Đã Đặt / Tối Đa</th>
                                                        <th>Hành Động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($tour->chuyentour as $chuyen)
                                                        <tr>
                                                            <td><strong>#00{{ $chuyen->maChuyen }}</strong></td>
                                                            <td>{{ \Carbon\Carbon::parse($chuyen->ngayBatDau)->format('d/m/Y') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($chuyen->ngayKetThuc)->format('d/m/Y') }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $chuyen->soLuongDaDat >= $chuyen->soLuongToiDa ? 'danger' : 'success' }}">
                                                                    {{ $chuyen->soLuongDaDat }} / {{ $chuyen->soLuongToiDa }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-info btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modalKhach{{ $chuyen->maChuyen }}">
                                                                    Xem Khách
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted py-5">Chưa có chuyến tour nào.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>