  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.webp" alt=""> -->
        <h1 class="sitename">TravelTime</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('home') }}" class="active">Trang chủ</a></li>
          <li class="dropdown"><a href="#"><span>Loại Du Lịch</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              @foreach($danhmucs as $loaiTour)
                <li>
                  <a href="{{ route('tour.list', ['query' => $loaiTour->tenDanhMuc]) }}">
                    {{ $loaiTour->tenDanhMuc }}
                  </a>
                </li>
              @endforeach
            </ul>
          </li>
              

          <!-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li> -->
          <li><a href="{{ route('user.thongtinuser') }}">Thông tin tài khoản</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      @if(Auth::check())
        
        <div class="dropdown">
          <a class="btn-getstarted dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::user()->hoTen ?? Auth::user()->tenDangNhap }}
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('user.logout') }}">Đăng xuất</a></li>
          </ul>
        </div>
      @else

        <a class="btn-getstarted" href="{{ route('user.login') }}">Đăng Nhập</a>
      @endif


    </div>
  </header>