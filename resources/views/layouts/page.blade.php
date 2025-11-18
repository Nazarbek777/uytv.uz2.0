<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>Resido - Residence & Real Estate HTML Template</title>

    <!-- Custom CSS -->
    <link href="/template/assets/css/styles.css" rel="stylesheet">

    <!-- Custom Color Option -->
    <link href="/template/assets/css/colors.css" rel="stylesheet">

</head>

<body class="blue-skin">

<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div id="preloader">
    <div class="preloader"><span></span><span></span></div>
</div>

<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ================================a============================== -->
<div id="main-wrapper">

    <x-pages.header></x-pages.header>

    @yield('content')
    <x-pages.footer></x-pages.footer>

    <!-- Log In Modal -->
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="registermodal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
            <div class="modal-content" id="registermodal">
						<span class="mod-close" data-bs-dismiss="modal" aria-hidden="true">
							<span class="svg-icon text-main svg-icon-2hx">
								<svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
									<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
									<rect x="7" y="15.3137" width="12" height="2" rx="1"
                                          transform="rotate(-45 7 15.3137)" fill="currentColor"/>
									<rect x="8.41422" y="7" width="12" height="2" rx="1"
                                          transform="rotate(45 8.41422 7)" fill="currentColor"/>
								</svg>
							</span>
						</span>
                <div class="modal-body">
                    @php
                        $locale = app()->getLocale();
                    @endphp
                    <h4 class="modal-header-title">{{ $locale === 'uz' ? 'Kirish' : ($locale === 'ru' ? 'Войти' : 'Log In') }}</h4>
                    <div class="d-flex align-items-center justify-content-center mb-3">
								<span class="svg-icon text-main svg-icon-2hx">
									<svg width="80" height="80" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
										<path
                                            d="M15.8797 15.375C15.9797 15.075 15.9797 14.775 15.9797 14.475C15.9797 13.775 15.7797 13.075 15.4797 12.475C14.7797 11.275 13.4797 10.475 11.9797 10.475C11.7797 10.475 11.5797 10.475 11.3797 10.575C7.37971 11.075 4.67971 14.575 2.57971 18.075L10.8797 3.675C11.3797 2.775 12.5797 2.775 13.0797 3.675C13.1797 3.875 13.2797 3.975 13.3797 4.175C15.2797 7.575 16.9797 11.675 15.8797 15.375Z"
                                            fill="currentColor"/>
										<path opacity="0.3"
                                              d="M20.6797 20.6749C16.7797 20.6749 12.3797 20.275 9.57972 17.575C10.2797 18.075 11.0797 18.375 11.9797 18.375C13.4797 18.375 14.7797 17.5749 15.4797 16.2749C15.6797 15.9749 15.7797 15.675 15.7797 15.375V15.2749C16.8797 11.5749 15.2797 7.47495 13.2797 4.07495L21.6797 18.6749C22.2797 19.5749 21.6797 20.6749 20.6797 20.6749ZM8.67972 18.6749C8.17972 17.8749 7.97972 16.975 7.77972 15.975C7.37972 13.575 8.67972 10.775 11.3797 10.375C7.37972 10.875 4.67972 14.375 2.57972 17.875C2.47972 18.075 2.27972 18.375 2.17972 18.575C1.67972 19.475 2.27972 20.475 3.27972 20.475H10.3797C9.67972 20.175 9.07972 19.3749 8.67972 18.6749Z"
                                              fill="currentColor"/>
									</svg>
								</span>
                    </div>
                    <div class="login-form">
                        <form id="login-form" method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div id="login-error" class="alert alert-danger d-none mb-3"></div>

                            <div class="form-floating mb-3">
                                <input type="email" name="email" id="login-email" class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                                <label>{{ $locale === 'uz' ? 'Email manzil' : ($locale === 'ru' ? 'Адрес электронной почты' : 'Email address') }}</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" name="password" id="login-password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                                <label>{{ $locale === 'uz' ? 'Parol' : ($locale === 'ru' ? 'Пароль' : 'Password') }}</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="flex-shrink-0 flex-first">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="remember" id="save-pass" value="1">
                                            <label class="form-check-label" for="save-pass">{{ $locale === 'uz' ? 'Eslab qolish' : ($locale === 'ru' ? 'Запомнить меня' : 'Remember Me') }}</label>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 flex-first">
                                        <a href="#" class="link fw-medium">{{ $locale === 'uz' ? 'Parolni unutdingizmi?' : ($locale === 'ru' ? 'Забыли пароль?' : 'Forgot Password?') }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-main fw-medium full-width rounded-2" id="login-submit">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <span class="btn-text">{{ $locale === 'uz' ? 'Kirish' : ($locale === 'ru' ? 'Войти' : 'Log In') }}</span>
                                </button>
                            </div>

                        </form>
                    </div>
                    <div class="modal-divider"><span>{{ $locale === 'uz' ? 'Yoki orqali kirish' : ($locale === 'ru' ? 'Или войти через' : 'Or login via') }}</span></div>
                    <div class="social-login mb-3">
                        <ul>
                            <li><a href="#" class="btn connect-fb"><i class="bi bi-facebook"></i>Facebook</a></li>
                            <li><a href="#" class="btn connect-google"><i class="bi bi-google"></i>Google+</a></li>
                        </ul>
                    </div>
                    <div class="text-center">
                        <p class="mt-4">{{ $locale === 'uz' ? 'Hisobingiz yo\'qmi?' : ($locale === 'ru' ? 'Нет аккаунта?' : 'Haven\'t Any Account?') }} <a href="create-account.html" class="link fw-medium">{{ $locale === 'uz' ? 'Hisob yaratish' : ($locale === 'ru' ? 'Создать аккаунт' : 'Create An Account') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>


</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="/template/assets/js/jquery.min.js"></script>
<script src="/template/assets/js/popper.min.js"></script>
<script src="/template/assets/js/bootstrap.min.js"></script>
<script src="/template/assets/js/rangeslider.js"></script>
<script src="/template/assets/js/select2.min.js"></script>
<script src="/template/assets/js/jquery.magnific-popup.min.js"></script>
<script src="/template/assets/js/slick.js"></script>
<script src="/template/assets/js/slider-bg.js"></script>
<script src="/template/assets/js/lightbox.js"></script>
<script src="/template/assets/js/imagesloaded.js"></script>

<script src="/template/assets/js/custom.js"></script>
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->

<!-- Login Form AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const loginError = document.getElementById('login-error');
    const loginSubmit = document.getElementById('login-submit');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Hide error
            loginError.classList.add('d-none');
            
            // Show loading
            const spinner = loginSubmit.querySelector('.spinner-border');
            const btnText = loginSubmit.querySelector('.btn-text');
            spinner.classList.remove('d-none');
            btnText.textContent = '{{ app()->getLocale() === "uz" ? "Kirilmoqda..." : (app()->getLocale() === "ru" ? "Вход..." : "Logging in...") }}';
            loginSubmit.disabled = true;
            
            // Get form data
            const formData = new FormData(loginForm);
            
            // Send AJAX request
            fetch(loginForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw data;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Success - redirect
                    window.location.href = data.redirect || '{{ route("provider.properties.index") }}';
                } else {
                    // Error
                    let errorMsg = data.message || 'Login failed. Please try again.';
                    if (data.errors) {
                        const errors = Object.values(data.errors).flat();
                        errorMsg = errors.join('\\n');
                    }
                    showError(errorMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMsg = '{{ app()->getLocale() === "uz" ? "Xatolik yuz berdi. Iltimos, qayta urinib ko\'ring." : (app()->getLocale() === "ru" ? "Произошла ошибка. Пожалуйста, попробуйте снова." : "An error occurred. Please try again.") }}';
                if (error.message) {
                    errorMsg = error.message;
                } else if (error.errors) {
                    const errors = Object.values(error.errors).flat();
                    errorMsg = errors.join('\\n');
                }
                showError(errorMsg);
            })
            .finally(() => {
                // Hide loading
                spinner.classList.add('d-none');
                btnText.textContent = '{{ app()->getLocale() === "uz" ? "Kirish" : (app()->getLocale() === "ru" ? "Войти" : "Log In") }}';
                loginSubmit.disabled = false;
            });
        });
    }
    
    function showError(message) {
        loginError.textContent = message;
        loginError.classList.remove('d-none');
    }
});
</script>

</body>
</html>
