<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="{{ route('dashboard') }}" class="app-brand-link">
        <span class="app-brand-logo demo">
          <span style="color: var(--bs-primary)">
            <img src="{{ asset('assets/img/logo/logo.svg') }}" alt="Logo Digitalisasi Desa" style="max-width: 50px;">
          </span>
        </span>
        <span class="app-brand-text demo menu-text fw-semibold ms-2">EDISI</span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M8.47365 11.7183C8.11707 12.0749 8.11707 12.6531 8.47365 13.0097L12.071 16.607C12.4615 16.9975 12.4615 17.6305 12.071 18.021C11.6805 18.4115 11.0475 18.4115 10.657 18.021L5.83009 13.1941C5.37164 12.7356 5.37164 11.9924 5.83009 11.5339L10.657 6.707C11.0475 6.31653 11.6805 6.31653 12.071 6.707C12.4615 7.09747 12.4615 7.73053 12.071 8.121L8.47365 11.7183Z"
            fill-opacity="0.9" />
          <path
            d="M14.3584 11.8336C14.0654 12.1266 14.0654 12.6014 14.3584 12.8944L18.071 16.607C18.4615 16.9975 18.4615 17.6305 18.071 18.021C17.6805 18.4115 17.0475 18.4115 16.657 18.021L11.6819 13.0459C11.3053 12.6693 11.3053 12.0587 11.6819 11.6821L16.657 6.707C17.0475 6.31653 17.6805 6.31653 18.071 6.707C18.4615 7.09747 18.4615 7.73053 18.071 8.121L14.3584 11.8336Z"
            fill-opacity="0.4" />
        </svg>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ri-home-smile-line"></i>
                <div data-i18n="Dashboards">Dashboards</div>
                {{-- <div class="badge bg-danger rounded-pill ms-auto">5</div> --}}
            </a>
        </li>

        @if (auth()->user()->can('User Access') || auth()->user()->can('Role Access') || auth()->user()->can('Permission Access'))

            <li class="menu-item {{ (request()->segment(1) == 'users' || request()->segment(1) == 'roles' || request()->segment(1) == 'permissions') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ri-group-line"></i>
                    <div data-i18n="Administrator">Administrator</div>
                </a>

                <ul class="menu-sub">
                    @if (auth()->user()->can('User Access'))
                        <li class="menu-item {{ request()->segment(1) == 'users' ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="menu-link">
                            <div data-i18n="Users Management">Users Management</div>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('Role Access'))
                        <li class="menu-item {{ request()->segment(1) == 'roles' ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}" class="menu-link">
                            <div data-i18n="Roles">Roles</div>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('Permission Access'))
                        <li class="menu-item {{ request()->segment(1) == 'permissions' ? 'active' : '' }}">
                            <a href="{{ route('permissions.index') }}" class="menu-link">
                            <div data-i18n="Permission">Permission</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>

        @endif

        @if (
            auth()->user()->can('Data Warga Access') ||
            auth()->user()->can('Info RW Access') ||
            auth()->user()->can('Berita RW Access') ||
            auth()->user()->can('Berita RW Kategori Access') ||
            auth()->user()->can('Lapor RW Access') ||
            auth()->user()->can('Tanya RW Access') ||
            auth()->user()->can('Survei RW Access') ||
            auth()->user()->can('Layanan Surat Access')
        )

            <!-- Edukasi -->
            <li class="menu-item {{ (request()->segment(1) == 'datawarga' || request()->segment(1) == 'inforw' || request()->segment(1) == 'laporrw' || request()->segment(1) == 'tanyarw' || request()->segment(1) == 'surveirw' || request()->segment(1) == 'kategoriberita' || request()->segment(1) == 'berita' || request()->segment(1) == 'layanansurat') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ri-community-line"></i>
                    <div data-i18n="Edukasi">Edukasi</div>
                </a>

                <ul class="menu-sub">
                    @if (auth()->user()->can('Data Warga Access'))
                        <li class="menu-item {{ request()->segment(1) == 'datawarga' ? 'active' : '' }}">
                            <a href="{{ route('datawarga.index') }}" class="menu-link">
                            <div data-i18n="Data Warga">Data Warga</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->can('Info RW Access'))
                        <li class="menu-item {{ request()->segment(1) == 'inforw' ? 'active' : '' }}">
                            <a href="{{ route('inforw.index') }}" class="menu-link">
                            <div data-i18n="Info RW">Info RW</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->can('Layanan Surat Access'))
                        <li class="menu-item {{ request()->segment(1) == 'layanansurat' ? 'active' : '' }}">
                            <a href="{{ route('layanansurat.index') }}" class="menu-link">
                            <div data-i18n="Layanan Surat">Layanan Surat</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->can('Berita RW Kategori Access') && auth()->user()->can('Berita RW Access'))
                        <li class="menu-item {{ (request()->segment(1) == 'kategoriberita' || request()->segment(1) == 'berita') ? 'open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <div data-i18n="Berita RW">Berita RW</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item {{ request()->segment(1) == 'kategoriberita' ? 'active' : '' }}">
                                    <a href="{{ route('kategoriberita.index') }}" class="menu-link">
                                      <div data-i18n="Kategori">Kategori</div>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->segment(1) == 'berita' ? 'active' : '' }}">
                                    <a href="{{ route('berita.index') }}" class="menu-link">
                                      <div data-i18n="Berita">Berita</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="menu-item {{ request()->segment(1) == 'berita' ? 'active' : '' }}">
                            <a href="{{ route('berita.index') }}" class="menu-link">
                            <div data-i18n="Berita RW">Berita RW</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->can('Lapor RW Access'))
                        <li class="menu-item {{ request()->segment(1) == 'laporrw' ? 'active' : '' }}">
                            <a href="{{ route('laporrw.index') }}" class="menu-link">
                            <div data-i18n="Lapor RW">Lapor RW</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->can('Tanya RW Access'))
                        <li class="menu-item {{ request()->segment(1) == 'tanyarw' ? 'active' : '' }}">
                            <a href="{{ route('tanyarw.index') }}" class="menu-link">
                            <div data-i18n="Tanya RW">Tanya RW</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->can('Survei RW Access'))
                        <li class="menu-item {{ request()->segment(1) == 'surveirw' ? 'active' : '' }}">
                            <a href="{{ route('surveirw.index') }}" class="menu-link">
                            <div data-i18n="Survei RW">Survei RW</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>

        @endif

        @if (
            auth()->user()->can('Loker RW Access') ||
            auth()->user()->can('Forum RW Access') ||
            auth()->user()->can('Donasi RW Access') ||
            auth()->user()->can('Tani RW Access')
        )
            <!-- Diskusi -->
            <li class="menu-item {{ (request()->segment(1) == 'lokerrw' || request()->segment(1) == 'forumrw' || request()->segment(1) == 'forumpengurusrw' || request()->segment(1) == 'tanirw' || request()->segment(1) == 'ireda') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ri-discuss-line"></i>
                    <div data-i18n="Diskusi">Diskusi</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                        <div data-i18n="Sehat RW">Sehat RW</div>
                        </a>
                    </li>

                    @if (auth()->user()->can('Loker RW Access'))
                        <li class="menu-item {{ request()->segment(1) == 'lokerrw' ? 'active' : '' }}">
                            <a href="{{ route('lokerrw.index') }}" class="menu-link">
                            <div data-i18n="Loker RW">Loker RW</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->can('Forum RW Access'))
                        @if (auth()->user()->hasRole('Warga'))
                            <li class="menu-item {{ request()->segment(1) == 'forumrw' ? 'active' : '' }}">
                                <a href="{{ route('forumrw.index') }}" class="menu-link">
                                <div data-i18n="Forum RW">Forum RW</div>
                                </a>
                            </li>
                        @else
                            <li class="menu-item {{ (request()->segment(1) == 'forumrw' || request()->segment(1) == 'forumpengurusrw') ? 'open' : '' }}">
                                <a href="javascript:void(0);" class="menu-link menu-toggle">
                                    <div data-i18n="Forum RW">Forum RW</div>
                                </a>
                                <ul class="menu-sub">
                                    <li class="menu-item {{ request()->segment(1) == 'forumrw' ? 'active' : '' }}">
                                        <a href="{{ route('forumrw.index') }}" class="menu-link">
                                        <div data-i18n="Warga">Warga</div>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ request()->segment(1) == 'forumpengurusrw' ? 'active' : '' }}">
                                        <a href="{{ route('forumrw.pengurus.index') }}" class="menu-link">
                                        <div data-i18n="Pengurus">Pengurus</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif

                    @if (auth()->user()->can('Tani RW Access'))
                        <li class="menu-item {{ request()->segment(1) == 'tanirw' ? 'active' : '' }}">
                            <a href="{{ route('tanirw.index') }}" class="menu-link">
                            <div data-i18n="Tani RW">Tani RW</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->can('Donasi RW Access'))
                        <li class="menu-item {{ request()->segment(1) == 'ireda' ? 'active' : '' }}">
                            <a href="{{ route('ireda.index') }}" class="menu-link">
                            <div data-i18n="IREDA">IREDA</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
            auth()->user()->can('Pasar RW Access') ||
            auth()->user()->can('IKlan RW Access')
        )

            <!-- Solusi -->
            <li class="menu-item {{ (request()->segment(1) == 'pasarrw' || request()->segment(1) == 'adsrw') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ri-lightbulb-flash-line"></i>
                    <div data-i18n="Solusi">Solusi</div>
                </a>

                <ul class="menu-sub">
                    @if (auth()->user()->can('Pasar RW Access'))
                        <li class="menu-item {{ request()->segment(1) == 'pasarrw' ? 'active' : '' }}">
                            <a href="{{ route('pasarrw.index') }}" class="menu-link">
                            <div data-i18n="Pasar RW">Pasar RW</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->can('Iklan RW Access'))
                        <li class="menu-item {{ request()->segment(1) == 'adsrw' ? 'active' : '' }}">
                            <a href="{{ route('adsrw.index') }}" class="menu-link">
                            <div data-i18n="Ads RW">Ads RW</div>
                            </a>
                        </li>
                    @endif

                    <li class="menu-item">
                        <a href="#" class="menu-link">
                        <div data-i18n="Mitra BUMRW">Mitra BUMRW</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>
</aside>
