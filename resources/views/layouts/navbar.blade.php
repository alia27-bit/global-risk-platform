<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <i class="bi bi-globe-americas me-2"></i>
            {{ config('app.name') }}
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item dropdown me-3">

                    <a class="nav-link dropdown-toggle"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">

                        <i class="bi bi-translate"></i>

                        {{ strtoupper(app()->getLocale()) }}

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>

                            <a class="dropdown-item"
                               href="{{ route('language.switch','id') }}">

                                🇮🇩 Indonesia

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item"
                               href="{{ route('language.switch','en') }}">

                                🇺🇸 English

                            </a>

                        </li>

                    </ul>

                </li>

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">

                        <i class="bi bi-person-circle"></i>

                        {{ auth()->user()->nama }}

                        <span class="badge bg-primary ms-1">

                            {{ ucfirst(auth()->user()->peran) }}

                        </span>

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>

                            <a class="dropdown-item"
                               href="{{ route('profile.edit') }}">

                                <i class="bi bi-person me-2"></i>

                                {{ __('messages.profile') }}

                            </a>

                        </li>

                        <li>

                            <hr class="dropdown-divider">

                        </li>

                        <li>

                            <form action="{{ route('logout') }}"
                                  method="POST">

                                @csrf

                                <button
                                    class="dropdown-item text-danger">

                                    <i class="bi bi-box-arrow-right me-2"></i>

                                    {{ __('messages.logout') }}

                                </button>

                            </form>

                        </li>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

</nav>
