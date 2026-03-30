(function () {
  var STORAGE_KEY = 'tlm-theme';

  function safeGetItem(key) {
    try {
      return localStorage.getItem(key);
    } catch (e) {
      return null;
    }
  }

  function safeSetItem(key, value) {
    try {
      localStorage.setItem(key, value);
    } catch (e) {
      // Ignore storage failures (private mode, strict settings)
    }
  }

  function preferredTheme() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
      ? 'dark'
      : 'light';
  }

  function currentTheme() {
    return document.documentElement.classList.contains('theme-dark') ? 'dark' : 'light';
  }

  function applyTheme(theme) {
    var root = document.documentElement;
    var resolved = theme === 'dark' ? 'dark' : 'light';

    root.classList.toggle('theme-dark', resolved === 'dark');
    root.setAttribute('data-theme', resolved);
  }

  function updateButton(button, theme) {
    var icon = button.querySelector('[data-theme-icon]');
    var glyph = button.querySelector('[data-theme-glyph]');
    var label = button.querySelector('[data-theme-label]');
    var isDark = theme === 'dark';

    button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
    button.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');

    if (!icon) {
      if (glyph) {
        glyph.textContent = isDark ? '\u2600' : '\u263E';
      }
      if (label) {
        label.textContent = isDark ? 'Light' : 'Dark';
      }
      return;
    }

    icon.classList.remove('ri-sun-line', 'ri-moon-line');
    icon.classList.add(isDark ? 'ri-sun-line' : 'ri-moon-line');

    if (label) {
      label.textContent = isDark ? 'Light' : 'Dark';
    }

    if (glyph) {
      glyph.textContent = isDark ? '\u2600' : '\u263E';
    }
  }

  function bindButton(button) {
    if (button.dataset.themeBound === '1') {
      return;
    }

    button.dataset.themeBound = '1';

    button.addEventListener('click', function () {
      var nextTheme = currentTheme() === 'dark' ? 'light' : 'dark';
      safeSetItem(STORAGE_KEY, nextTheme);
      applyTheme(nextTheme);

      var allButtons = document.querySelectorAll('[data-theme-toggle]');
      allButtons.forEach(function (btn) {
        updateButton(btn, nextTheme);
      });
    });
  }

  function createFloatingToggleIfMissing() {
    var hasDeclaredToggle = document.querySelector('[data-theme-toggle]');
    if (hasDeclaredToggle) {
      return;
    }

    if (!document.body) {
      return;
    }

    var button = document.createElement('button');
    button.type = 'button';
    button.setAttribute('data-theme-toggle', '');
    button.className = 'theme-toggle-btn theme-toggle-floating';
    button.setAttribute('aria-label', 'Toggle dark mode');
    button.setAttribute('title', 'Toggle dark mode');
    button.innerHTML = '<span data-theme-glyph aria-hidden="true">\u263E</span>';
    document.body.appendChild(button);
  }

  function initThemeToggle() {
    var initialTheme = safeGetItem(STORAGE_KEY) || preferredTheme();
    applyTheme(initialTheme);

    createFloatingToggleIfMissing();

    var buttons = document.querySelectorAll('[data-theme-toggle]');
    buttons.forEach(function (button) {
      bindButton(button);
      updateButton(button, initialTheme);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThemeToggle);
  } else {
    initThemeToggle();
  }

  window.initializeThemeToggle = initThemeToggle;
})();
