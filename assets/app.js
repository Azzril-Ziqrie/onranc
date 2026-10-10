(() => {
  'use strict';
  const root = document.documentElement;
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  root.classList.add('js');

  /* ---- Split text into per-char / per-word spans (Character Appear / Text Reveal) ---- */
  const split = (el, mode) => {
    const text = el.textContent;
    el.textContent = '';
    const parts = mode === 'chars' ? [...text] : text.split(/(\s+)/);
    let i = 0;
    parts.forEach((p) => {
      if (/^\s+$/.test(p)) { el.append(document.createTextNode(' ')); return; }
      const s = document.createElement('span');
      s.className = mode === 'chars' ? 'c' : 'w';
      s.style.setProperty('--i', i++);
      s.setAttribute('aria-hidden', 'true');
      s.textContent = p;
      el.append(s);
    });
    if (mode === 'words') el.setAttribute('aria-label', text);
  };

  document.querySelectorAll('[data-chars]').forEach((el) => split(el, 'chars'));
  document.querySelectorAll('[data-reveal]').forEach((el, n) => {
    split(el, 'words');
    el.style.setProperty('--d', 200 + n * 160 + 'ms');
  });

  const play = () => {
    document.querySelectorAll('[data-chars], [data-reveal]').forEach((el) => el.classList.add('is-in'));
    setTimeout(() => document.querySelectorAll('.notify, .foot').forEach((el) => el.classList.add('is-in')), reduce ? 0 : 700);
  };
  requestAnimationFrame(() => requestAnimationFrame(play));

  /* ---- Text Scramble ---- */
  const GLYPHS = '/\\_-+=<>[]{}#*01';
  const scramble = (el, to, ms = 600) => {
    if (reduce) { el.textContent = to; return Promise.resolve(); }
    const from = el.textContent;
    const len = Math.max(from.length, to.length);
    const t0 = performance.now();
    return new Promise((done) => {
      const tick = (t) => {
        const p = Math.min(1, (t - t0) / ms);
        let out = '';
        for (let i = 0; i < len; i++) {
          const settle = i / len;
          if (p > settle + .25) out += to[i] || '';
          else if (to[i] === ' ') out += ' ';
          else out += GLYPHS[(Math.random() * GLYPHS.length) | 0];
        }
        el.textContent = out;
        p < 1 ? requestAnimationFrame(tick) : (el.textContent = to, done());
      };
      requestAnimationFrame(tick);
    });
  };

  const btn = document.querySelector('[data-scramble]');
  if (btn && !reduce) {
    const label = btn.textContent;
    btn.addEventListener('pointerenter', () => { if (!btn.disabled) scramble(btn, label, 350); });
  }

  /* ---- Subscribe form (progressive enhancement; works without JS via normal POST) ---- */
  const form = document.getElementById('notify');
  const status = document.getElementById('status');
  if (!form) return;
  const input = form.elements.email;
  const submit = form.querySelector('button');
  const say = (msg) => scramble(status, msg, 500);
  const valid = (v) => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v);

  input.addEventListener('input', () => input.removeAttribute('aria-invalid'));

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = input.value.trim();
    if (!valid(email)) {
      input.setAttribute('aria-invalid', 'true');
      input.focus();
      say('Please enter a valid e-mail.');
      return;
    }
    submit.disabled = true;
    try {
      const res = await fetch(form.action, {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: new FormData(form),
      });
      const data = await res.json();
      if (!res.ok || !data.ok) throw new Error(data.message || 'Something went wrong.');
      form.reset();
      say(data.message);
    } catch (err) {
      submit.disabled = false;
      say(err.message || 'Something went wrong. Try again.');
    }
  });
})();
