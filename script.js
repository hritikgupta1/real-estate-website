// mobile menu
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.menu');
  if (btn && menu) btn.addEventListener('click', ()=> menu.classList.toggle('show'));

  // simple stories carousel
  const quotes = document.querySelectorAll('.carousel blockquote');
  const dotsWrap = document.querySelector('.carousel .dots');
  if (quotes.length && dotsWrap) {
    let current = 0; // track active index

    quotes.forEach((q, i) => {
      const b = document.createElement('button');
      if (i===0) { q.classList.add('active'); b.classList.add('active'); }
      b.addEventListener('click', () => showSlide(i));
      dotsWrap.appendChild(b);
    });

    const dots = dotsWrap.querySelectorAll('button');

    function showSlide(i) {
      quotes[current].classList.remove('active');
      dots[current].classList.remove('active');
      current = i;
      quotes[current].classList.add('active');
      dots[current].classList.add('active');
    }

    // ✅ Auto-slide every 5 seconds
    setInterval(() => {
      let next = (current + 1) % quotes.length;
      showSlide(next);
    }, 4000);
  }
});
