<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    // Auto-open login modal if show_login flag is set
    @if(session('show_login'))
        const loginModal = new bootstrap.Modal(document.getElementById('login'));
        loginModal.show();
    @endif
    
    const loginForm = document.getElementById('login-form');
    const loginError = document.getElementById('login-error');
    const loginSubmit = document.getElementById('login-submit');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Hide error
            if (loginError) {
                loginError.classList.add('d-none');
            }
            
            // Show loading
            const spinner = loginSubmit.querySelector('.spinner-border');
            const btnText = loginSubmit.querySelector('.btn-text');
            if (spinner) spinner.classList.remove('d-none');
            if (btnText) btnText.textContent = '{{ app()->getLocale() === "uz" ? "Kirilmoqda..." : (app()->getLocale() === "ru" ? "Вход..." : "Logging in...") }}';
            loginSubmit.disabled = true;
            
            // Get form data
            const formData = new FormData(loginForm);
            
            // Send AJAX request
            fetch(loginForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(response => {
                return response.json().then(data => {
                    if (!response.ok) {
                        throw data;
                    }
                    return data;
                });
            })
            .then(data => {
                if (data.success) {
                    // Success - redirect
                    window.location.href = data.redirect || '{{ route("provider.properties.index") }}';
                } else {
                    // Error
                    let errorMsg = data.message || '{{ app()->getLocale() === "uz" ? "Kirish muvaffaqiyatsiz. Iltimos, qayta urinib ko\'ring." : (app()->getLocale() === "ru" ? "Вход не удался. Пожалуйста, попробуйте снова." : "Login failed. Please try again.") }}';
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
                if (spinner) spinner.classList.add('d-none');
                if (btnText) btnText.textContent = '{{ app()->getLocale() === "uz" ? "Kirish" : (app()->getLocale() === "ru" ? "Войти" : "Log In") }}';
                loginSubmit.disabled = false;
            });
        });
    }
    
    function showError(message) {
        if (loginError) {
            loginError.textContent = message;
            loginError.classList.remove('d-none');
        }
    }
});
</script>

<!-- Chatbot UI -->
<div id="uytv-chatbot">
    <!-- Floating Button -->
    <button id="chatbot-toggle" class="chatbot-button" title="{{ app()->getLocale() === 'uz' ? 'AI Yordamchi' : (app()->getLocale() === 'ru' ? 'AI Помощник' : 'AI Assistant') }}">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z" fill="currentColor"/>
        </svg>
        <span class="chatbot-badge">AI</span>
    </button>

    <!-- Chat Modal -->
    <div id="chatbot-modal" class="chatbot-modal">
        <div class="chatbot-header">
            <div class="d-flex align-items-center">
                <div class="chatbot-avatar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor"/>
                    </svg>
                </div>
                <div class="ms-2">
                    <h6 class="mb-0 fw-bold">UYTV AI</h6>
                    <small class="text-muted">{{ app()->getLocale() === 'uz' ? 'Yordamchi' : (app()->getLocale() === 'ru' ? 'Помощник' : 'Assistant') }}</small>
                </div>
            </div>
            <div class="chatbot-actions">
                <button class="btn btn-sm btn-link p-1" id="chatbot-clear" title="{{ app()->getLocale() === 'uz' ? 'Tozalash' : (app()->getLocale() === 'ru' ? 'Очистить' : 'Clear') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill="currentColor"/>
                    </svg>
                </button>
                <button class="btn btn-sm btn-link p-1" id="chatbot-close" title="{{ app()->getLocale() === 'uz' ? 'Yopish' : (app()->getLocale() === 'ru' ? 'Закрыть' : 'Close') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill="currentColor"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="chatbot-body" id="chatbot-messages">
            <!-- Messages will be inserted here -->
        </div>
        <div class="chatbot-properties" id="chatbot-properties" style="display: none;">
            <!-- Recommended properties will be shown here -->
        </div>
        <div class="chatbot-footer">
            <div class="input-group">
                <input type="text" id="chatbot-input" class="form-control" placeholder="{{ app()->getLocale() === 'uz' ? 'Xabar yozing...' : (app()->getLocale() === 'ru' ? 'Введите сообщение...' : 'Type a message...') }}" autocomplete="off">
                <button class="btn btn-primary" id="chatbot-send" type="button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" fill="currentColor"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#uytv-chatbot {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

/* Floating Button - Modern Design */
.chatbot-button {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0987f5 0%, #0774d4 100%) !important;
    color: white;
    border: 3px solid white;
    box-shadow: 0 8px 24px rgba(9, 135, 245, 0.4), 0 0 0 0 rgba(9, 135, 245, 0.5);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 8px 24px rgba(9, 135, 245, 0.4), 0 0 0 0 rgba(9, 135, 245, 0.5);
    }
    50% {
        box-shadow: 0 8px 24px rgba(9, 135, 245, 0.4), 0 0 0 8px rgba(9, 135, 245, 0);
    }
}

.chatbot-button:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 12px 32px rgba(9, 135, 245, 0.6), 0 0 0 0 rgba(9, 135, 245, 0.5);
    animation: none;
}

.chatbot-button svg {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.chatbot-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(238, 90, 111, 0.4);
    border: 2px solid white;
    letter-spacing: 0.5px;
}

/* Chat Modal - Glassmorphism Design */
.chatbot-modal {
    position: absolute;
    bottom: 88px;
    right: 0;
    width: 400px;
    max-width: calc(100vw - 48px);
    height: 640px;
    max-height: calc(100vh - 112px);
    background: white;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.5);
    display: none;
    flex-direction: column;
    overflow: hidden;
    backdrop-filter: blur(10px);
    opacity: 0;
    transform: translateY(20px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chatbot-modal.active {
    display: flex;
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* Header - Gradient Design */
.chatbot-header {
    padding: 20px 20px 16px;
    background: linear-gradient(135deg, #0987f5 0%, #0774d4 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 12px rgba(9, 135, 245, 0.2);
    position: relative;
    overflow: hidden;
}

.chatbot-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.chatbot-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(255,255,255,0.3);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    position: relative;
    z-index: 1;
}

.chatbot-avatar svg {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.chatbot-header h6 {
    font-weight: 700;
    font-size: 16px;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: relative;
    z-index: 1;
}

.chatbot-header small {
    opacity: 0.9;
    font-size: 12px;
    position: relative;
    z-index: 1;
}

.chatbot-actions {
    display: flex;
    gap: 4px;
    position: relative;
    z-index: 1;
}

.chatbot-actions button {
    color: white !important;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
}

.chatbot-actions button:hover {
    background: rgba(255,255,255,0.25);
    transform: scale(1.1);
}

/* Body - Smooth Scroll */
.chatbot-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
    scroll-behavior: smooth;
}

.chatbot-body::-webkit-scrollbar {
    width: 6px;
}

.chatbot-body::-webkit-scrollbar-track {
    background: transparent;
}

.chatbot-body::-webkit-scrollbar-thumb {
    background: #d0d0d0;
    border-radius: 3px;
}

.chatbot-body::-webkit-scrollbar-thumb:hover {
    background: #b0b0b0;
}

/* Messages - Modern Bubbles */
.chatbot-message {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chatbot-message.user {
    align-items: flex-end;
}

.chatbot-message.assistant {
    align-items: flex-start;
}

.message-bubble {
    max-width: 85%;
    padding: 14px 18px;
    border-radius: 20px;
    word-wrap: break-word;
    line-height: 1.5;
    font-size: 14px;
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.chatbot-message.user .message-bubble {
    background: linear-gradient(135deg, #0987f5 0%, #0774d4 100%);
    color: white;
    border-bottom-right-radius: 4px;
    box-shadow: 0 4px 12px rgba(9, 135, 245, 0.3);
}

.chatbot-message.assistant .message-bubble {
    background: white;
    color: #333;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
}

/* Link button in messages */
.chatbot-link-btn {
    display: inline-block;
    margin-top: 12px;
    padding: 10px 18px;
    background: linear-gradient(135deg, #0987f5 0%, #0774d4 100%);
    color: white !important;
    text-decoration: none;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 12px rgba(9, 135, 245, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
}

.chatbot-link-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(9, 135, 245, 0.4);
    color: white !important;
}

.chatbot-link-btn:active {
    transform: translateY(0);
}

/* Typing Indicator */
.chatbot-message.assistant .message-bubble.typing {
    background: white;
    padding: 16px 18px;
}

.typing-dots {
    display: flex;
    gap: 6px;
    align-items: center;
}

.typing-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #0987f5;
    animation: typing 1.4s infinite;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.5;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

/* Properties Section */
.chatbot-properties {
    max-height: 280px;
    overflow-y: auto;
    padding: 18px 20px;
    border-top: 1px solid #f0f0f0;
    background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
    box-shadow: 0 -2px 8px rgba(0,0,0,0.05);
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chatbot-properties::-webkit-scrollbar {
    width: 6px;
}

.chatbot-properties::-webkit-scrollbar-thumb {
    background: #d0d0d0;
    border-radius: 3px;
}

.property-card {
    display: flex;
    gap: 14px;
    padding: 14px;
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    margin-bottom: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    color: inherit;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
}

.property-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(9, 135, 245, 0.1), transparent);
    transition: left 0.5s;
}

.property-card:hover::before {
    left: 100%;
}

.property-card:hover {
    border-color: #0987f5;
    box-shadow: 0 8px 24px rgba(9, 135, 245, 0.2);
    transform: translateY(-4px);
}

.property-card img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    flex-shrink: 0;
    transition: transform 0.3s;
}

.property-card:hover img {
    transform: scale(1.05);
}

.property-info {
    flex: 1;
    min-width: 0;
}

.property-info h6 {
    font-size: 15px;
    margin-bottom: 6px;
    color: #1a1a1a;
    font-weight: 600;
    line-height: 1.4;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.property-info .price {
    color: #0987f5;
    font-weight: 700;
    font-size: 18px;
    margin-bottom: 4px;
    text-shadow: 0 1px 2px rgba(9, 135, 245, 0.1);
}

.property-info .location {
    color: #666;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Footer - Modern Input */
.chatbot-footer {
    padding: 16px 20px;
    border-top: 1px solid #f0f0f0;
    background: white;
    box-shadow: 0 -2px 8px rgba(0,0,0,0.05);
}

.chatbot-footer .input-group {
    gap: 10px;
    align-items: center;
}

.chatbot-footer input {
    border-radius: 28px;
    border: 2px solid #e8e8e8;
    padding: 12px 20px;
    font-size: 14px;
    transition: all 0.3s;
    background: #f8f9fa;
}

.chatbot-footer input:focus {
    border-color: #0987f5;
    box-shadow: 0 0 0 4px rgba(9, 135, 245, 0.1);
    background: white;
    outline: none;
}

.chatbot-footer button {
    border-radius: 50%;
    width: 48px;
    height: 48px;
    padding: 0;
    background: linear-gradient(135deg, #0987f5 0%, #0774d4 100%) !important;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(9, 135, 245, 0.3);
    transition: all 0.3s;
}

.chatbot-footer button:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 6px 16px rgba(9, 135, 245, 0.4);
}

.chatbot-footer button:active {
    transform: scale(0.95);
}

/* Responsive */
@media (max-width: 768px) {
    #uytv-chatbot {
        bottom: 16px;
        right: 16px;
    }
    
    .chatbot-modal {
        width: calc(100vw - 32px);
        height: calc(100vh - 96px);
        bottom: 80px;
        right: 0;
        border-radius: 20px;
    }
    
    .chatbot-button {
        width: 56px;
        height: 56px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotModal = document.getElementById('chatbot-modal');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotClear = document.getElementById('chatbot-clear');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotProperties = document.getElementById('chatbot-properties');
    const locale = '{{ app()->getLocale() }}';
    const STORAGE_KEY = 'uytv_chatbot_history_' + locale;
    let isOpen = false;

    // Load conversation history from localStorage
    function loadChatHistory() {
        try {
            // Only load if messages container is empty
            if (chatbotMessages.children.length > 0) {
                return [];
            }

            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                const history = JSON.parse(saved);
                if (history && history.length > 0) {
                    history.forEach(msg => {
                        if (msg.role === 'user' || msg.role === 'assistant') {
                            // Add message without saving (to avoid duplicate saves)
                            const messageId = 'msg-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                            const messageDiv = document.createElement('div');
                            messageDiv.id = messageId;
                            messageDiv.className = 'chatbot-message ' + msg.role;
                            
                            const bubble = document.createElement('div');
                            bubble.className = 'message-bubble';
                            bubble.innerHTML = formatMessage(msg.content);
                            
                            messageDiv.appendChild(bubble);
                            chatbotMessages.appendChild(messageDiv);
                        }
                    });
                    
                    // Scroll to bottom after loading
                    setTimeout(() => {
                        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
                    }, 100);
                    
                    return history;
                }
            }
        } catch (e) {
            console.error('Error loading chat history:', e);
        }
        return [];
    }

    // Save conversation history to localStorage
    function saveChatHistory() {
        try {
            const messages = Array.from(chatbotMessages.children).map(msgEl => {
                const bubble = msgEl.querySelector('.message-bubble');
                if (!bubble) return null;
                
                const role = msgEl.classList.contains('user') ? 'user' : 'assistant';
                const content = bubble.textContent || bubble.innerText || '';
                
                return {
                    role: role,
                    content: content,
                    timestamp: Date.now()
                };
            }).filter(msg => msg !== null);
            
            localStorage.setItem(STORAGE_KEY, JSON.stringify(messages));
        } catch (e) {
            console.error('Error saving chat history:', e);
        }
    }

    // Clear localStorage
    function clearLocalStorage() {
        try {
            localStorage.removeItem(STORAGE_KEY);
        } catch (e) {
            console.error('Error clearing localStorage:', e);
        }
    }

    // Toggle modal
    if (chatbotToggle) {
        chatbotToggle.addEventListener('click', function() {
            isOpen = !isOpen;
            if (isOpen) {
                chatbotModal.classList.add('active');
                chatbotInput.focus();
                
                // Load saved history if exists, otherwise show welcome
                const history = loadChatHistory();
                if (history.length === 0) {
                    loadWelcomeMessage();
                }
            } else {
                chatbotModal.classList.remove('active');
            }
        });
    }

    // Close modal
    if (chatbotClose) {
        chatbotClose.addEventListener('click', function() {
            isOpen = false;
            chatbotModal.classList.remove('active');
        });
    }

    // Clear history
    if (chatbotClear) {
        chatbotClear.addEventListener('click', function() {
            if (confirm('{{ app()->getLocale() === "uz" ? "Barcha xabarlar o\'chirilsinmi?" : (app()->getLocale() === "ru" ? "Удалить все сообщения?" : "Clear all messages?") }}')) {
                clearHistory();
            }
        });
    }

    // Send message
    function sendMessage() {
        const message = chatbotInput.value.trim();
        if (!message) return;

        // Add user message
        addMessage('user', message);
        chatbotInput.value = '';

        // Show typing indicator
        const typingId = addTypingIndicator();

        // Send to API
        fetch('{{ route("chatbot.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: message,
                locale: locale
            })
        })
        .then(response => response.json())
        .then(data => {
            // Remove typing indicator
            const typingElement = document.getElementById(typingId);
            if (typingElement) typingElement.remove();

            if (data.success) {
                // Add assistant message (with link if available)
                let messageText = data.message;
                if (data.listings_url && data.has_filters) {
                    const linkText = '{{ app()->getLocale() === "uz" ? "Barcha mos uylarni ko\'rish" : (app()->getLocale() === "ru" ? "Смотреть все подходящие объекты" : "View all suitable properties") }}';
                    messageText += '\n\n<a href="' + data.listings_url + '" class="chatbot-link-btn" target="_blank">🔗 ' + linkText + '</a>';
                }
                addMessage('assistant', messageText);

                // Show properties if available (always show if properties exist)
                if (data.properties && data.properties.length > 0) {
                    showProperties(data.properties);
                } else {
                    hideProperties();
                }

                // Save to localStorage
                saveChatHistory();
            } else {
                addMessage('assistant', '{{ app()->getLocale() === "uz" ? "Xatolik yuz berdi. Iltimos, qayta urinib ko\'ring." : (app()->getLocale() === "ru" ? "Произошла ошибка. Пожалуйста, попробуйте снова." : "An error occurred. Please try again.") }}');
            }
        })
        .catch(error => {
            console.error('Chatbot error:', error);
            const typingElement = document.getElementById(typingId);
            if (typingElement) typingElement.remove();
            addMessage('assistant', '{{ app()->getLocale() === "uz" ? "Xatolik yuz berdi. Iltimos, qayta urinib ko\'ring." : (app()->getLocale() === "ru" ? "Произошла ошибка. Пожалуйста, попробуйте снова." : "An error occurred. Please try again.") }}');
        });
    }

    // Send button click
    if (chatbotSend) {
        chatbotSend.addEventListener('click', sendMessage);
    }

    // Enter key
    if (chatbotInput) {
        chatbotInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }

    // Add message
    function addMessage(role, content, isTyping = false) {
        const messageId = 'msg-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        const messageDiv = document.createElement('div');
        messageDiv.id = messageId;
        messageDiv.className = 'chatbot-message ' + role;
        
        const bubble = document.createElement('div');
        bubble.className = 'message-bubble';
        
        if (isTyping) {
            bubble.className += ' typing';
            bubble.innerHTML = '<div class="typing-dots"><span></span><span></span><span></span></div>';
        } else {
            // Format message - convert newlines to <br>, preserve formatting
            const formattedContent = formatMessage(content);
            bubble.innerHTML = formattedContent;
        }
        
        messageDiv.appendChild(bubble);
        chatbotMessages.appendChild(messageDiv);
        
        // Scroll to bottom
        setTimeout(() => {
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }, 100);
        
        // Save to localStorage (only if not typing indicator)
        if (!isTyping) {
            setTimeout(() => {
                saveChatHistory();
            }, 200);
        }
        
        return messageId;
    }

    // Format message - convert newlines and preserve formatting
    function formatMessage(text) {
        if (!text) return '';
        
        // First, extract and preserve links
        const linkPattern = /<a href="([^"]+)"[^>]*>(.*?)<\/a>/g;
        const links = [];
        let linkIndex = 0;
        let textWithPlaceholders = text.replace(linkPattern, (match, url, linkText) => {
            const placeholder = `__LINK_${linkIndex}__`;
            links[linkIndex] = { url, text: linkText };
            linkIndex++;
            return placeholder;
        });
        
        // Escape HTML (except placeholders)
        let formatted = textWithPlaceholders.replace(/&/g, '&amp;')
                           .replace(/</g, '&lt;')
                           .replace(/>/g, '&gt;');
        
        // Restore links with proper HTML
        links.forEach((link, index) => {
            const placeholder = `__LINK_${index}__`;
            formatted = formatted.replace(placeholder, `<a href="${link.url}" class="chatbot-link-btn" target="_blank" rel="noopener noreferrer">${link.text}</a>`);
        });
        
        // Convert newlines to <br>
        formatted = formatted.replace(/\n/g, '<br>');
        
        // Bold formatting **text**
        formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Numbered lists
        formatted = formatted.replace(/^\d+\.\s+(.+)$/gm, '<div style="margin: 4px 0; padding-left: 8px;">$1</div>');
        
        return formatted;
    }

    // Add typing indicator
    function addTypingIndicator() {
        return addMessage('assistant', '', true);
    }

    // Show properties
    function showProperties(properties) {
        if (!properties || properties.length === 0) {
            hideProperties();
            return;
        }
        
        chatbotProperties.innerHTML = '<div class="fw-bold mb-3" style="font-size: 15px; color: #1a1a1a;">{{ app()->getLocale() === "uz" ? "🏠 Tavsiya etilgan uy-joylar:" : (app()->getLocale() === "ru" ? "🏠 Рекомендуемая недвижимость:" : "🏠 Recommended properties:") }}</div>';
        
        properties.forEach((property, index) => {
            const card = document.createElement('a');
            card.href = property.url;
            card.className = 'property-card';
            card.target = '_blank';
            card.rel = 'noopener noreferrer';
            
            const imageSrc = property.featured_image || 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&h=300&fit=crop';
            const listingType = property.listing_type === 'sale' ? 
                ('{{ app()->getLocale() === "uz" ? "Sotuv" : (app()->getLocale() === "ru" ? "Продажа" : "Sale") }}') : 
                ('{{ app()->getLocale() === "uz" ? "Ijara" : (app()->getLocale() === "ru" ? "Аренда" : "Rent") }}');
            
            card.innerHTML = `
                <img src="${imageSrc}" alt="${property.title || 'Property'}">
                <div class="property-info">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span style="font-size: 11px; padding: 2px 8px; background: ${property.listing_type === 'sale' ? '#0987f5' : '#28a745'}; color: white; border-radius: 12px; font-weight: 600;">${listingType}</span>
                        ${property.property_type ? `<span style="font-size: 11px; color: #666;">${property.property_type}</span>` : ''}
                    </div>
                    <h6>${property.title || 'N/A'}</h6>
                    <div class="price">${property.price} ${property.currency || 'UZS'}</div>
                    <div class="location">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-right: 4px;">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>
                        </svg>
                        ${property.city || 'N/A'}
                        ${property.bedrooms ? ` • ${property.bedrooms} {{ app()->getLocale() === "uz" ? "xona" : (app()->getLocale() === "ru" ? "комн" : "bed") }}` : ''}
                    </div>
                </div>
            `;
            chatbotProperties.appendChild(card);
        });
        
        chatbotProperties.style.display = 'block';
        
        // Smooth scroll to properties
        setTimeout(() => {
            chatbotProperties.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 300);
    }

    // Hide properties
    function hideProperties() {
        chatbotProperties.style.display = 'none';
        chatbotProperties.innerHTML = '';
    }

    // Load welcome message
    function loadWelcomeMessage() {
        if (chatbotMessages.children.length === 0) {
            fetch('{{ route("chatbot.welcome") }}?locale=' + locale)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addMessage('assistant', data.message);
                        saveChatHistory();
                    }
                })
                .catch(error => console.error('Error loading welcome:', error));
        }
    }

    // Clear history
    function clearHistory() {
        chatbotMessages.innerHTML = '';
        hideProperties();
        
        // Clear localStorage
        clearLocalStorage();
        
        // Clear server-side history
        fetch('{{ route("chatbot.clear") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                locale: locale
            })
        })
        .then(() => {
            loadWelcomeMessage();
        })
        .catch(error => console.error('Error clearing history:', error));
    }

    // Initialize - Load saved history on page load
    if (chatbotModal) {
        // Check if we have saved history
        const saved = localStorage.getItem(STORAGE_KEY);
        if (!saved || JSON.parse(saved).length === 0) {
            // Only load welcome if no history exists
            // Welcome will be loaded when modal opens
        }
    }

    // Save history before page unload
    window.addEventListener('beforeunload', function() {
        saveChatHistory();
    });
});
</script>

</body>
</html>

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
    // Auto-open login modal if show_login flag is set
    @if(session('show_login'))
        const loginModal = new bootstrap.Modal(document.getElementById('login'));
        loginModal.show();
    @endif
    
    const loginForm = document.getElementById('login-form');
    const loginError = document.getElementById('login-error');
    const loginSubmit = document.getElementById('login-submit');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Hide error
            if (loginError) {
                loginError.classList.add('d-none');
            }
            
            // Show loading
            const spinner = loginSubmit.querySelector('.spinner-border');
            const btnText = loginSubmit.querySelector('.btn-text');
            if (spinner) spinner.classList.remove('d-none');
            if (btnText) btnText.textContent = '{{ app()->getLocale() === "uz" ? "Kirilmoqda..." : (app()->getLocale() === "ru" ? "Вход..." : "Logging in...") }}';
            loginSubmit.disabled = true;
            
            // Get form data
            const formData = new FormData(loginForm);
            
            // Send AJAX request
            fetch(loginForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(response => {
                return response.json().then(data => {
                    if (!response.ok) {
                        throw data;
                    }
                    return data;
                });
            })
            .then(data => {
                if (data.success) {
                    // Success - redirect
                    window.location.href = data.redirect || '{{ route("provider.properties.index") }}';
                } else {
                    // Error
                    let errorMsg = data.message || '{{ app()->getLocale() === "uz" ? "Kirish muvaffaqiyatsiz. Iltimos, qayta urinib ko\'ring." : (app()->getLocale() === "ru" ? "Вход не удался. Пожалуйста, попробуйте снова." : "Login failed. Please try again.") }}';
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
                if (spinner) spinner.classList.add('d-none');
                if (btnText) btnText.textContent = '{{ app()->getLocale() === "uz" ? "Kirish" : (app()->getLocale() === "ru" ? "Войти" : "Log In") }}';
                loginSubmit.disabled = false;
            });
        });
    }
    
    function showError(message) {
        if (loginError) {
            loginError.textContent = message;
            loginError.classList.remove('d-none');
        }
    }
});
</script>

<!-- Chatbot UI -->
<div id="uytv-chatbot">
    <!-- Floating Button -->
    <button id="chatbot-toggle" class="chatbot-button" title="{{ app()->getLocale() === 'uz' ? 'AI Yordamchi' : (app()->getLocale() === 'ru' ? 'AI Помощник' : 'AI Assistant') }}">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z" fill="currentColor"/>
        </svg>
        <span class="chatbot-badge">AI</span>
    </button>

    <!-- Chat Modal -->
    <div id="chatbot-modal" class="chatbot-modal">
        <div class="chatbot-header">
            <div class="d-flex align-items-center">
                <div class="chatbot-avatar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor"/>
                    </svg>
                </div>
                <div class="ms-2">
                    <h6 class="mb-0 fw-bold">UYTV AI</h6>
                    <small class="text-muted">{{ app()->getLocale() === 'uz' ? 'Yordamchi' : (app()->getLocale() === 'ru' ? 'Помощник' : 'Assistant') }}</small>
                </div>
            </div>
            <div class="chatbot-actions">
                <button class="btn btn-sm btn-link p-1" id="chatbot-clear" title="{{ app()->getLocale() === 'uz' ? 'Tozalash' : (app()->getLocale() === 'ru' ? 'Очистить' : 'Clear') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill="currentColor"/>
                    </svg>
                </button>
                <button class="btn btn-sm btn-link p-1" id="chatbot-close" title="{{ app()->getLocale() === 'uz' ? 'Yopish' : (app()->getLocale() === 'ru' ? 'Закрыть' : 'Close') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill="currentColor"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="chatbot-body" id="chatbot-messages">
            <!-- Messages will be inserted here -->
        </div>
        <div class="chatbot-properties" id="chatbot-properties" style="display: none;">
            <!-- Recommended properties will be shown here -->
        </div>
        <div class="chatbot-footer">
            <div class="input-group">
                <input type="text" id="chatbot-input" class="form-control" placeholder="{{ app()->getLocale() === 'uz' ? 'Xabar yozing...' : (app()->getLocale() === 'ru' ? 'Введите сообщение...' : 'Type a message...') }}" autocomplete="off">
                <button class="btn btn-primary" id="chatbot-send" type="button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" fill="currentColor"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#uytv-chatbot {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

/* Floating Button - Modern Design */
.chatbot-button {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0987f5 0%, #0774d4 100%) !important;
    color: white;
    border: 3px solid white;
    box-shadow: 0 8px 24px rgba(9, 135, 245, 0.4), 0 0 0 0 rgba(9, 135, 245, 0.5);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 8px 24px rgba(9, 135, 245, 0.4), 0 0 0 0 rgba(9, 135, 245, 0.5);
    }
    50% {
        box-shadow: 0 8px 24px rgba(9, 135, 245, 0.4), 0 0 0 8px rgba(9, 135, 245, 0);
    }
}

.chatbot-button:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 12px 32px rgba(9, 135, 245, 0.6), 0 0 0 0 rgba(9, 135, 245, 0.5);
    animation: none;
}

.chatbot-button svg {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.chatbot-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(238, 90, 111, 0.4);
    border: 2px solid white;
    letter-spacing: 0.5px;
}

/* Chat Modal - Glassmorphism Design */
.chatbot-modal {
    position: absolute;
    bottom: 88px;
    right: 0;
    width: 400px;
    max-width: calc(100vw - 48px);
    height: 640px;
    max-height: calc(100vh - 112px);
    background: white;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.5);
    display: none;
    flex-direction: column;
    overflow: hidden;
    backdrop-filter: blur(10px);
    opacity: 0;
    transform: translateY(20px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chatbot-modal.active {
    display: flex;
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* Header - Gradient Design */
.chatbot-header {
    padding: 20px 20px 16px;
    background: linear-gradient(135deg, #0987f5 0%, #0774d4 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 12px rgba(9, 135, 245, 0.2);
    position: relative;
    overflow: hidden;
}

.chatbot-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.chatbot-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(255,255,255,0.3);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    position: relative;
    z-index: 1;
}

.chatbot-avatar svg {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.chatbot-header h6 {
    font-weight: 700;
    font-size: 16px;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: relative;
    z-index: 1;
}

.chatbot-header small {
    opacity: 0.9;
    font-size: 12px;
    position: relative;
    z-index: 1;
}

.chatbot-actions {
    display: flex;
    gap: 4px;
    position: relative;
    z-index: 1;
}

.chatbot-actions button {
    color: white !important;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
}

.chatbot-actions button:hover {
    background: rgba(255,255,255,0.25);
    transform: scale(1.1);
}

/* Body - Smooth Scroll */
.chatbot-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
    scroll-behavior: smooth;
}

.chatbot-body::-webkit-scrollbar {
    width: 6px;
}

.chatbot-body::-webkit-scrollbar-track {
    background: transparent;
}

.chatbot-body::-webkit-scrollbar-thumb {
    background: #d0d0d0;
    border-radius: 3px;
}

.chatbot-body::-webkit-scrollbar-thumb:hover {
    background: #b0b0b0;
}

/* Messages - Modern Bubbles */
.chatbot-message {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chatbot-message.user {
    align-items: flex-end;
}

.chatbot-message.assistant {
    align-items: flex-start;
}

.message-bubble {
    max-width: 85%;
    padding: 14px 18px;
    border-radius: 20px;
    word-wrap: break-word;
    line-height: 1.5;
    font-size: 14px;
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.chatbot-message.user .message-bubble {
    background: linear-gradient(135deg, #0987f5 0%, #0774d4 100%);
    color: white;
    border-bottom-right-radius: 4px;
    box-shadow: 0 4px 12px rgba(9, 135, 245, 0.3);
}

.chatbot-message.assistant .message-bubble {
    background: white;
    color: #333;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
}

/* Link button in messages */
.chatbot-link-btn {
    display: inline-block;
    margin-top: 12px;
    padding: 10px 18px;
    background: linear-gradient(135deg, #0987f5 0%, #0774d4 100%);
    color: white !important;
    text-decoration: none;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 12px rgba(9, 135, 245, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
}

.chatbot-link-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(9, 135, 245, 0.4);
    color: white !important;
}

.chatbot-link-btn:active {
    transform: translateY(0);
}

/* Typing Indicator */
.chatbot-message.assistant .message-bubble.typing {
    background: white;
    padding: 16px 18px;
}

.typing-dots {
    display: flex;
    gap: 6px;
    align-items: center;
}

.typing-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #0987f5;
    animation: typing 1.4s infinite;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.5;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

/* Properties Section */
.chatbot-properties {
    max-height: 280px;
    overflow-y: auto;
    padding: 18px 20px;
    border-top: 1px solid #f0f0f0;
    background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
    box-shadow: 0 -2px 8px rgba(0,0,0,0.05);
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chatbot-properties::-webkit-scrollbar {
    width: 6px;
}

.chatbot-properties::-webkit-scrollbar-thumb {
    background: #d0d0d0;
    border-radius: 3px;
}

.property-card {
    display: flex;
    gap: 14px;
    padding: 14px;
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    margin-bottom: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    color: inherit;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
}

.property-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(9, 135, 245, 0.1), transparent);
    transition: left 0.5s;
}

.property-card:hover::before {
    left: 100%;
}

.property-card:hover {
    border-color: #0987f5;
    box-shadow: 0 8px 24px rgba(9, 135, 245, 0.2);
    transform: translateY(-4px);
}

.property-card img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    flex-shrink: 0;
    transition: transform 0.3s;
}

.property-card:hover img {
    transform: scale(1.05);
}

.property-info {
    flex: 1;
    min-width: 0;
}

.property-info h6 {
    font-size: 15px;
    margin-bottom: 6px;
    color: #1a1a1a;
    font-weight: 600;
    line-height: 1.4;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.property-info .price {
    color: #0987f5;
    font-weight: 700;
    font-size: 18px;
    margin-bottom: 4px;
    text-shadow: 0 1px 2px rgba(9, 135, 245, 0.1);
}

.property-info .location {
    color: #666;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Footer - Modern Input */
.chatbot-footer {
    padding: 16px 20px;
    border-top: 1px solid #f0f0f0;
    background: white;
    box-shadow: 0 -2px 8px rgba(0,0,0,0.05);
}

.chatbot-footer .input-group {
    gap: 10px;
    align-items: center;
}

.chatbot-footer input {
    border-radius: 28px;
    border: 2px solid #e8e8e8;
    padding: 12px 20px;
    font-size: 14px;
    transition: all 0.3s;
    background: #f8f9fa;
}

.chatbot-footer input:focus {
    border-color: #0987f5;
    box-shadow: 0 0 0 4px rgba(9, 135, 245, 0.1);
    background: white;
    outline: none;
}

.chatbot-footer button {
    border-radius: 50%;
    width: 48px;
    height: 48px;
    padding: 0;
    background: linear-gradient(135deg, #0987f5 0%, #0774d4 100%) !important;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(9, 135, 245, 0.3);
    transition: all 0.3s;
}

.chatbot-footer button:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 6px 16px rgba(9, 135, 245, 0.4);
}

.chatbot-footer button:active {
    transform: scale(0.95);
}

/* Responsive */
@media (max-width: 768px) {
    #uytv-chatbot {
        bottom: 16px;
        right: 16px;
    }
    
    .chatbot-modal {
        width: calc(100vw - 32px);
        height: calc(100vh - 96px);
        bottom: 80px;
        right: 0;
        border-radius: 20px;
    }
    
    .chatbot-button {
        width: 56px;
        height: 56px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotModal = document.getElementById('chatbot-modal');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotClear = document.getElementById('chatbot-clear');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotProperties = document.getElementById('chatbot-properties');
    const locale = '{{ app()->getLocale() }}';
    const STORAGE_KEY = 'uytv_chatbot_history_' + locale;
    let isOpen = false;

    // Load conversation history from localStorage
    function loadChatHistory() {
        try {
            // Only load if messages container is empty
            if (chatbotMessages.children.length > 0) {
                return [];
            }

            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                const history = JSON.parse(saved);
                if (history && history.length > 0) {
                    history.forEach(msg => {
                        if (msg.role === 'user' || msg.role === 'assistant') {
                            // Add message without saving (to avoid duplicate saves)
                            const messageId = 'msg-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                            const messageDiv = document.createElement('div');
                            messageDiv.id = messageId;
                            messageDiv.className = 'chatbot-message ' + msg.role;
                            
                            const bubble = document.createElement('div');
                            bubble.className = 'message-bubble';
                            bubble.innerHTML = formatMessage(msg.content);
                            
                            messageDiv.appendChild(bubble);
                            chatbotMessages.appendChild(messageDiv);
                        }
                    });
                    
                    // Scroll to bottom after loading
                    setTimeout(() => {
                        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
                    }, 100);
                    
                    return history;
                }
            }
        } catch (e) {
            console.error('Error loading chat history:', e);
        }
        return [];
    }

    // Save conversation history to localStorage
    function saveChatHistory() {
        try {
            const messages = Array.from(chatbotMessages.children).map(msgEl => {
                const bubble = msgEl.querySelector('.message-bubble');
                if (!bubble) return null;
                
                const role = msgEl.classList.contains('user') ? 'user' : 'assistant';
                const content = bubble.textContent || bubble.innerText || '';
                
                return {
                    role: role,
                    content: content,
                    timestamp: Date.now()
                };
            }).filter(msg => msg !== null);
            
            localStorage.setItem(STORAGE_KEY, JSON.stringify(messages));
        } catch (e) {
            console.error('Error saving chat history:', e);
        }
    }

    // Clear localStorage
    function clearLocalStorage() {
        try {
            localStorage.removeItem(STORAGE_KEY);
        } catch (e) {
            console.error('Error clearing localStorage:', e);
        }
    }

    // Toggle modal
    if (chatbotToggle) {
        chatbotToggle.addEventListener('click', function() {
            isOpen = !isOpen;
            if (isOpen) {
                chatbotModal.classList.add('active');
                chatbotInput.focus();
                
                // Load saved history if exists, otherwise show welcome
                const history = loadChatHistory();
                if (history.length === 0) {
                    loadWelcomeMessage();
                }
            } else {
                chatbotModal.classList.remove('active');
            }
        });
    }

    // Close modal
    if (chatbotClose) {
        chatbotClose.addEventListener('click', function() {
            isOpen = false;
            chatbotModal.classList.remove('active');
        });
    }

    // Clear history
    if (chatbotClear) {
        chatbotClear.addEventListener('click', function() {
            if (confirm('{{ app()->getLocale() === "uz" ? "Barcha xabarlar o\'chirilsinmi?" : (app()->getLocale() === "ru" ? "Удалить все сообщения?" : "Clear all messages?") }}')) {
                clearHistory();
            }
        });
    }

    // Send message
    function sendMessage() {
        const message = chatbotInput.value.trim();
        if (!message) return;

        // Add user message
        addMessage('user', message);
        chatbotInput.value = '';

        // Show typing indicator
        const typingId = addTypingIndicator();

        // Send to API
        fetch('{{ route("chatbot.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: message,
                locale: locale
            })
        })
        .then(response => response.json())
        .then(data => {
            // Remove typing indicator
            const typingElement = document.getElementById(typingId);
            if (typingElement) typingElement.remove();

            if (data.success) {
                // Add assistant message (with link if available)
                let messageText = data.message;
                if (data.listings_url && data.has_filters) {
                    const linkText = '{{ app()->getLocale() === "uz" ? "Barcha mos uylarni ko\'rish" : (app()->getLocale() === "ru" ? "Смотреть все подходящие объекты" : "View all suitable properties") }}';
                    messageText += '\n\n<a href="' + data.listings_url + '" class="chatbot-link-btn" target="_blank">🔗 ' + linkText + '</a>';
                }
                addMessage('assistant', messageText);

                // Show properties if available (always show if properties exist)
                if (data.properties && data.properties.length > 0) {
                    showProperties(data.properties);
                } else {
                    hideProperties();
                }

                // Save to localStorage
                saveChatHistory();
            } else {
                addMessage('assistant', '{{ app()->getLocale() === "uz" ? "Xatolik yuz berdi. Iltimos, qayta urinib ko\'ring." : (app()->getLocale() === "ru" ? "Произошла ошибка. Пожалуйста, попробуйте снова." : "An error occurred. Please try again.") }}');
            }
        })
        .catch(error => {
            console.error('Chatbot error:', error);
            const typingElement = document.getElementById(typingId);
            if (typingElement) typingElement.remove();
            addMessage('assistant', '{{ app()->getLocale() === "uz" ? "Xatolik yuz berdi. Iltimos, qayta urinib ko\'ring." : (app()->getLocale() === "ru" ? "Произошла ошибка. Пожалуйста, попробуйте снова." : "An error occurred. Please try again.") }}');
        });
    }

    // Send button click
    if (chatbotSend) {
        chatbotSend.addEventListener('click', sendMessage);
    }

    // Enter key
    if (chatbotInput) {
        chatbotInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }

    // Add message
    function addMessage(role, content, isTyping = false) {
        const messageId = 'msg-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        const messageDiv = document.createElement('div');
        messageDiv.id = messageId;
        messageDiv.className = 'chatbot-message ' + role;
        
        const bubble = document.createElement('div');
        bubble.className = 'message-bubble';
        
        if (isTyping) {
            bubble.className += ' typing';
            bubble.innerHTML = '<div class="typing-dots"><span></span><span></span><span></span></div>';
        } else {
            // Format message - convert newlines to <br>, preserve formatting
            const formattedContent = formatMessage(content);
            bubble.innerHTML = formattedContent;
        }
        
        messageDiv.appendChild(bubble);
        chatbotMessages.appendChild(messageDiv);
        
        // Scroll to bottom
        setTimeout(() => {
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }, 100);
        
        // Save to localStorage (only if not typing indicator)
        if (!isTyping) {
            setTimeout(() => {
                saveChatHistory();
            }, 200);
        }
        
        return messageId;
    }

    // Format message - convert newlines and preserve formatting
    function formatMessage(text) {
        if (!text) return '';
        
        // First, extract and preserve links
        const linkPattern = /<a href="([^"]+)"[^>]*>(.*?)<\/a>/g;
        const links = [];
        let linkIndex = 0;
        let textWithPlaceholders = text.replace(linkPattern, (match, url, linkText) => {
            const placeholder = `__LINK_${linkIndex}__`;
            links[linkIndex] = { url, text: linkText };
            linkIndex++;
            return placeholder;
        });
        
        // Escape HTML (except placeholders)
        let formatted = textWithPlaceholders.replace(/&/g, '&amp;')
                           .replace(/</g, '&lt;')
                           .replace(/>/g, '&gt;');
        
        // Restore links with proper HTML
        links.forEach((link, index) => {
            const placeholder = `__LINK_${index}__`;
            formatted = formatted.replace(placeholder, `<a href="${link.url}" class="chatbot-link-btn" target="_blank" rel="noopener noreferrer">${link.text}</a>`);
        });
        
        // Convert newlines to <br>
        formatted = formatted.replace(/\n/g, '<br>');
        
        // Bold formatting **text**
        formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Numbered lists
        formatted = formatted.replace(/^\d+\.\s+(.+)$/gm, '<div style="margin: 4px 0; padding-left: 8px;">$1</div>');
        
        return formatted;
    }

    // Add typing indicator
    function addTypingIndicator() {
        return addMessage('assistant', '', true);
    }

    // Show properties
    function showProperties(properties) {
        if (!properties || properties.length === 0) {
            hideProperties();
            return;
        }
        
        chatbotProperties.innerHTML = '<div class="fw-bold mb-3" style="font-size: 15px; color: #1a1a1a;">{{ app()->getLocale() === "uz" ? "🏠 Tavsiya etilgan uy-joylar:" : (app()->getLocale() === "ru" ? "🏠 Рекомендуемая недвижимость:" : "🏠 Recommended properties:") }}</div>';
        
        properties.forEach((property, index) => {
            const card = document.createElement('a');
            card.href = property.url;
            card.className = 'property-card';
            card.target = '_blank';
            card.rel = 'noopener noreferrer';
            
            const imageSrc = property.featured_image || 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&h=300&fit=crop';
            const listingType = property.listing_type === 'sale' ? 
                ('{{ app()->getLocale() === "uz" ? "Sotuv" : (app()->getLocale() === "ru" ? "Продажа" : "Sale") }}') : 
                ('{{ app()->getLocale() === "uz" ? "Ijara" : (app()->getLocale() === "ru" ? "Аренда" : "Rent") }}');
            
            card.innerHTML = `
                <img src="${imageSrc}" alt="${property.title || 'Property'}">
                <div class="property-info">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span style="font-size: 11px; padding: 2px 8px; background: ${property.listing_type === 'sale' ? '#0987f5' : '#28a745'}; color: white; border-radius: 12px; font-weight: 600;">${listingType}</span>
                        ${property.property_type ? `<span style="font-size: 11px; color: #666;">${property.property_type}</span>` : ''}
                    </div>
                    <h6>${property.title || 'N/A'}</h6>
                    <div class="price">${property.price} ${property.currency || 'UZS'}</div>
                    <div class="location">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-right: 4px;">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>
                        </svg>
                        ${property.city || 'N/A'}
                        ${property.bedrooms ? ` • ${property.bedrooms} {{ app()->getLocale() === "uz" ? "xona" : (app()->getLocale() === "ru" ? "комн" : "bed") }}` : ''}
                    </div>
                </div>
            `;
            chatbotProperties.appendChild(card);
        });
        
        chatbotProperties.style.display = 'block';
        
        // Smooth scroll to properties
        setTimeout(() => {
            chatbotProperties.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 300);
    }

    // Hide properties
    function hideProperties() {
        chatbotProperties.style.display = 'none';
        chatbotProperties.innerHTML = '';
    }

    // Load welcome message
    function loadWelcomeMessage() {
        if (chatbotMessages.children.length === 0) {
            fetch('{{ route("chatbot.welcome") }}?locale=' + locale)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addMessage('assistant', data.message);
                        saveChatHistory();
                    }
                })
                .catch(error => console.error('Error loading welcome:', error));
        }
    }

    // Clear history
    function clearHistory() {
        chatbotMessages.innerHTML = '';
        hideProperties();
        
        // Clear localStorage
        clearLocalStorage();
        
        // Clear server-side history
        fetch('{{ route("chatbot.clear") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                locale: locale
            })
        })
        .then(() => {
            loadWelcomeMessage();
        })
        .catch(error => console.error('Error clearing history:', error));
    }

    // Initialize - Load saved history on page load
    if (chatbotModal) {
        // Check if we have saved history
        const saved = localStorage.getItem(STORAGE_KEY);
        if (!saved || JSON.parse(saved).length === 0) {
            // Only load welcome if no history exists
            // Welcome will be loaded when modal opens
        }
    }

    // Save history before page unload
    window.addEventListener('beforeunload', function() {
        saveChatHistory();
    });
});
</script>

</body>
</html>
