<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ninja Control</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- criar mascaras -->
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100..900&display=swap" rel="stylesheet">
    <!-- Animate.css (for alert animations) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Ícone -->
    <link rel="icon" type="image/x-icon" href="../assets/img/icons/ninja_lock_icon.ico">

    <style>
        /* Fundo com gradiente animado */
        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .bg-animated-gradient {
            background: linear-gradient(45deg, #000000, #14213d, #708d81, #e5e5e5, #708d81, #14213d);
            background-size: 400% 400%;
            animation: gradientAnimation 20s ease infinite;
            height: 100vh;
            width: 100%; 
        }

        /* Animações de entrada/saída */
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideInLeft {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(-100%); opacity: 0; }
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes scaleOut {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(0);
                opacity: 0;
            }
        }

        #loader-container {
            z-index: 9999 !important;
        }


        .loader {
            border-radius: 50%;
        }

        .welcome-message {
            font-family: 'Noto Sans', sans-serif;
            font-size: 3rem;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: slideInRight 1s ease, slideInLeft 1s ease 3s forwards;
        }

        .ninja-image-animate {
            animation: scaleIn 0.5s ease forwards, scaleOut 0.5s ease 1.5s forwards;
        }

        .animacaoCena {
            font-family: 'Noto Sans', sans-serif;
            font-size: 3rem;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

          .bgBtn {
            background: linear-gradient(45deg,rgb(0, 0, 0),rgb(115, 114, 115),rgb(0, 0, 0));
            background-size: 400% 400%;
            animation: gradientAnim 8s ease infinite;
        }
        @keyframes gradientAnim {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        /* Custom defaults for alert animations using Animate.css variables */
        #alert-box {
            --animate-duration: 0.4s; /* default entry */
            animation-timing-function: cubic-bezier(.22,.9,.36,1); /* default ease-out feel */
        }
        /* Fallback/custom alert animations using CSS transitions (more reliable control) */
        #alert-box.alert-enter { transform: translateY(-8px); opacity: 0; }
        #alert-box.alert-enter-active { transform: translateY(0); opacity: 1; transition: transform var(--alert-entry-duration,0.45s) var(--alert-entry-timing,cubic-bezier(.22,.9,.36,1)), opacity var(--alert-entry-duration,0.45s) var(--alert-entry-timing,cubic-bezier(.22,.9,.36,1)); }
        #alert-box.alert-exit { transform: translateY(0); opacity: 1; }
        #alert-box.alert-exit-active { transform: translateY(-8px); opacity: 0; transition: transform var(--alert-exit-duration,0.28s) var(--alert-exit-timing,cubic-bezier(.55,0,.1,1)), opacity var(--alert-exit-duration,0.28s) var(--alert-exit-timing,cubic-bezier(.55,0,.1,1)); }
    </style>
</head>

<body class="bg-animated-gradient flex items-center justify-center">    

    <!-- Loader Spinner -->
    <div id="loader-container" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex flex-col items-center justify-center">
        <div class="flex items-center space-x-4 bg-white px-6 py-4 rounded-lg shadow-lg">
            <div class="loader border-4 border-teal-600 border-t-transparent rounded-full w-8 h-8 animate-spin"></div>
            <span id="loader-message" class="text-gray-700 font-semibold">Carregando...</span>
        </div>
    </div>

    <!-- ShowAlert -->
    <div id="alert-box" class="hidden fixed top-6 right-6 left-6 md:left-auto md:right-6 px-4 py-3 rounded-lg shadow-lg text-white bg-teal-600 flex items-center gap-3 z-990 max-w-sm w-auto" style="min-width:220px; z-index: 999 !important;">
        <i id="alert-icon" class="fas fa-info-circle text-xl md:text-2xl"></i>
        <span id="alert-message" class="font-semibold text-sm md:text-base flex-1"></span>
        <button id="alert-close" class="ml-2 text-white text-lg hover:text-gray-200 focus:outline-none">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <script>
        (function($){
            $(function(){
                var $alertBox = $('#alert-box');
                var $alertIcon = $('#alert-icon');
                var $alertMessage = $('#alert-message');
                var $alertClose = $('#alert-close');

                var types = {
                    success: { bg: 'bg-green-600', icon: 'fas fa-check-circle' },
                    error: { bg: 'bg-red-600', icon: 'fas fa-exclamation-circle' },
                    warning: { bg: 'bg-yellow-500', icon: 'fas fa-exclamation-triangle' },
                    info: { bg: 'bg-teal-600', icon: 'fas fa-info-circle' }
                };

                function startCloseAnimation(exitClass, exitDuration, exitTiming, useCssTransition){
                    exitClass = exitClass || ($alertBox.data('alertExitClass') || 'animate__fadeOutUp');
                    exitDuration = exitDuration || ($alertBox.data('alertExitDuration') || '0.28s');
                    exitTiming = exitTiming || ($alertBox.data('alertExitTiming') || 'cubic-bezier(.55,0,.1,1)');

                    // set exit timing and duration
                    $alertBox[0].style.setProperty('--animate-duration', exitDuration);
                    $alertBox[0].style.animationTimingFunction = exitTiming;

                    // If using CSS transition fallback, use transition classes
                    if (useCssTransition) {
                        $alertBox.removeClass('alert-enter alert-enter-active');
                        // force reflow
                        void $alertBox[0].offsetWidth;
                        $alertBox.addClass('alert-exit');
                        // apply exit duration/timing
                        $alertBox[0].style.setProperty('--alert-exit-duration', exitDuration);
                        $alertBox[0].style.setProperty('--alert-exit-timing', exitTiming);
                        // trigger active
                        void $alertBox[0].offsetWidth;
                        $alertBox.addClass('alert-exit-active');
                    } else {
                        // remove any entry class, force reflow, then add exit class so animation triggers
                        var entryClass = $alertBox.data('alertEntryClass') || 'animate__fadeInDown';
                        $alertBox.removeClass(entryClass + ' animate__animated');
                        // force reflow
                        void $alertBox[0].offsetWidth;
                        $alertBox.addClass('animate__animated ' + exitClass);
                    }

                    if (useCssTransition) {
                        // wait for transitionend instead
                        $alertBox.one('transitionend', function(){
                            $alertBox.addClass('hidden')
                                     .removeClass('alert-exit alert-exit-active');
                            $alertBox.removeData('alertTimeout');
                            $alertBox[0].style.removeProperty('--alert-exit-duration');
                            $alertBox[0].style.removeProperty('--alert-exit-timing');
                        });
                    } else {
                        $alertBox.one('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', function(){
                            $alertBox.addClass('hidden')
                                     .removeClass('animate__animated ' + entryClass + ' ' + exitClass);
                            $alertBox.removeData('alertTimeout');
                            // reset to default entry timing
                            $alertBox[0].style.setProperty('--animate-duration', '0.4s');
                            $alertBox[0].style.animationTimingFunction = 'cubic-bezier(.22,.9,.36,1)';
                        });
                    }
                }

                function showAlert(message, type, optsOrMs){
                    type = type || 'info';
                    $alertMessage.text(message);

                    $alertBox.removeClass('bg-green-600 bg-red-600 bg-yellow-500 bg-teal-600')
                             .addClass((types[type] && types[type].bg) || types.info.bg);

                    $alertIcon.attr('class', ((types[type] && types[type].icon) || types.info.icon) + ' text-xl md:text-2xl');

                    // support backwards-compatible third param: number = autoCloseMs
                    var opts = {};
                    if (typeof optsOrMs === 'number') opts.autoCloseMs = optsOrMs;
                    else if (typeof optsOrMs === 'object' && optsOrMs) opts = optsOrMs;

                    var entryClass = opts.entry || 'animate__fadeInDown';
                    var exitClass = opts.exit || 'animate__fadeOutUp';
                    var entryDuration = opts.entryDuration || '0.45s';
                    var exitDuration = opts.exitDuration || '0.28s';
                    var entryTiming = opts.entryTiming || 'cubic-bezier(.22,.9,.36,1)';
                    var exitTiming = opts.exitTiming || 'cubic-bezier(.55,0,.1,1)';
                    var closeAfter = (typeof opts.autoCloseMs === 'number') ? opts.autoCloseMs : 3000;

                    // store animation preferences on element for close routine
                    $alertBox.data('alertEntryClass', entryClass);
                    $alertBox.data('alertExitClass', exitClass);
                    $alertBox.data('alertExitDuration', exitDuration);
                    $alertBox.data('alertExitTiming', exitTiming);

                    // clear previous
                    var prevTimeout = $alertBox.data('alertTimeout');
                    if(prevTimeout) clearTimeout(prevTimeout);
                    $alertBox.off('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd');

                    // show with entry timing
                    $alertBox[0].style.setProperty('--animate-duration', entryDuration);
                    $alertBox[0].style.animationTimingFunction = entryTiming;
                    // determine whether to use CSS transition fallback (default true for reliability)
                    var useCssTransition = (typeof opts.useCssTransition === 'boolean') ? opts.useCssTransition : true;
                    // ensure visible first
                    $alertBox.removeClass('hidden');
                    if (useCssTransition) {
                        // apply entry duration/timing
                        $alertBox[0].style.setProperty('--alert-entry-duration', entryDuration);
                        $alertBox[0].style.setProperty('--alert-entry-timing', entryTiming);
                        $alertBox.removeClass('alert-exit alert-exit-active');
                        void $alertBox[0].offsetWidth;
                        $alertBox.addClass('alert-enter');
                        void $alertBox[0].offsetWidth;
                        $alertBox.addClass('alert-enter-active');
                    } else {
                        // remove any animation classes, force reflow, then add entry animation so it runs
                        $alertBox.removeClass(exitClass + ' animate__animated ' + entryClass);
                        void $alertBox[0].offsetWidth;
                        $alertBox.addClass('animate__animated ' + entryClass);
                    }

                    // auto close
                    var timeout = setTimeout(function(){
                        startCloseAnimation(exitClass, exitDuration, exitTiming, useCssTransition);
                    }, closeAfter);
                    $alertBox.data('alertTimeout', timeout);

                    // close button
                    $alertClose.off('click').on('click', function(e){
                        var t = $alertBox.data('alertTimeout');
                        if(t) clearTimeout(t);
                        startCloseAnimation(exitClass, exitDuration, exitTiming, useCssTransition);
                    });
                }

                // expose globally
                window.showAlert = showAlert;

                // loader
                window.loaderM = function(mensagem, estado){
                    var $loaderContainer = $('#loader-container');
                    var $loaderMessage = $('#loader-message');
                    if(estado){
                        $loaderMessage.text(mensagem || 'Carregando...');
                        $loaderContainer.removeClass('hidden');
                    } else {
                        $loaderContainer.addClass('hidden');
                    }
                };
            });
        })(jQuery);
    </script>
</body>
</html>