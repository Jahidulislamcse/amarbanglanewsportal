<style>
  .pwa-install-prompt {
    position: fixed;
    left: 12px;
    right: 12px;
    bottom: 16px;
    z-index: 2147483647;
    display: none;
    justify-content: center;
    pointer-events: none;
  }

  .pwa-install-prompt.is-visible {
    display: flex;
  }

  .pwa-install-prompt__box {
    width: min(100%, 420px);
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 8px;
    box-shadow: 0 12px 34px rgba(0, 0, 0, 0.22);
    padding: 14px;
    pointer-events: auto;
  }

  .pwa-install-prompt__title {
    margin: 0 0 4px;
    color: #111111;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.3;
  }

  .pwa-install-prompt__text {
    margin: 0 0 12px;
    color: #444444;
    font-size: 14px;
    line-height: 1.45;
  }

  .pwa-install-prompt__actions {
    display: flex;
    gap: 8px;
  }

  .pwa-install-prompt__button {
    flex: 1;
    min-height: 42px;
    border: 0;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
  }

  .pwa-install-prompt__button--install {
    background: #CD1D23;
    color: #ffffff;
  }

  .pwa-install-prompt__button--close {
    background: #eeeeee;
    color: #222222;
  }
</style>

<div class="pwa-install-prompt" id="pwaInstallPrompt" role="dialog" aria-live="polite" aria-label="Install app">
  <div class="pwa-install-prompt__box">
    <h3 class="pwa-install-prompt__title">আমার বাংলা অ্যাপ ইনস্টল করুন</h3>
    
    <p class="pwa-install-prompt__text" id="pwaInstallText">
        আরও সহজ, দ্রুত ও নিরাপদে আমার বাংলা ব্যবহার করতে অ্যাপটি ইনস্টল করুন।
    </p>
    <div class="pwa-install-prompt__actions">
        <button class="pwa-install-prompt__button pwa-install-prompt__button--install"
            type="button"
            id="pwaInstallButton">
            ইনস্টল করুন
        </button>
    
        <button class="pwa-install-prompt__button pwa-install-prompt__button--close"
            type="button"
            id="pwaInstallLater">
            পরে
        </button>
    
        <button class="pwa-install-prompt__button pwa-install-prompt__button--close"
            type="button"
            id="pwaAlreadyInstalled">
             করেছি
        </button>
    </div>
  </div>
</div>

<script>
(function () {
  var promptEvent = null;
  var prompt = document.getElementById('pwaInstallPrompt');
  var installButton = document.getElementById('pwaInstallButton');
  var installText = document.getElementById('pwaInstallText');
    var laterButton = document.getElementById('pwaInstallLater');
    var alreadyInstalledButton = document.getElementById('pwaAlreadyInstalled');
    
    var installedKey = 'amar_bangla_pwa_installed';
    var dismissedUntilKey = 'amar_bangla_pwa_dismissed_until';
  var installedKey = 'amar_bangla_pwa_installed';
  var installHelpUrl = "{{ route('install.app') }}";

  function isStandalone() {
    return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
  }

    function showPrompt() {
        var dismissedUntil = parseInt(localStorage.getItem(dismissedUntilKey) || '0', 10);
    
        if (
            !prompt ||
            localStorage.getItem(installedKey) === '1' ||
            isStandalone() ||
            Date.now() < dismissedUntil
        ) {
            return;
        }
    
        if (installText && !promptEvent) {
            installText.textContent =
                'আরও সহজ, দ্রুত ও নিরাপদে আমার বাংলা ব্যবহার করতে অ্যাপটি ইনস্টল করুন।';
        }
    
        prompt.classList.add('is-visible');
    }
    
    if (laterButton) {
        laterButton.addEventListener('click', function () {
            var threeHours = 3 * 60 * 60 * 1000;
    
            localStorage.setItem(
                dismissedUntilKey,
                Date.now() + threeHours
            );
    
            hidePrompt();
        });
    }
    
    if (alreadyInstalledButton) {
        alreadyInstalledButton.addEventListener('click', function () {
            localStorage.setItem(installedKey, '1');
            hidePrompt();
        });
    }
    
    window.addEventListener('load', function () {
        if (isStandalone()) {
            localStorage.setItem(installedKey, '1');
            return;
        }
    
        setTimeout(showPrompt, 2000);
    });

  function hidePrompt() {
    if (prompt) {
      prompt.classList.remove('is-visible');
    }
  }

  window.addEventListener('beforeinstallprompt', function (event) {
    event.preventDefault();
    promptEvent = event;
    if (installText) {
      installText.textContent = 'Get quick access from your phone home screen.';
    }
    setTimeout(showPrompt, 500);
  });

  window.addEventListener('appinstalled', function () {
    localStorage.setItem(installedKey, '1');
    hidePrompt();
    promptEvent = null;
  });

  if (installButton) {
    installButton.addEventListener('click', function () {
      if (!promptEvent) {
        hidePrompt();
        window.location.href = installHelpUrl;
        return;
      }

      promptEvent.prompt();
      promptEvent.userChoice.finally(function () {
        promptEvent = null;
        hidePrompt();
      });
    });
  }
})();
</script>
