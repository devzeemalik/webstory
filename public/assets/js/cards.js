import { cardMargin, cards, lightboxEl, player, setCardMargin, setCards, setCardWidth } from "./globals.js";
import { resetStyles } from './swipe-down-to-close.js';

/**
 * Initializes listeners for cards, including showing the lightbox on click
 * and showing/hiding the background cards on hover.
 */
export function initializeCards() {
  lightboxEl.addEventListener('click', () => {
    document.body.classList.toggle('lightbox-open');
  });

  setCards(document.querySelectorAll('.entry-point-card-container'));
  setCardMargin(parseFloat(getComputedStyle(cards[0]).marginRight))
  setCardWidth(cardMargin + cards[0].offsetWidth)

  const stories = player.getStories();

  cards.forEach((card, idx) => {
    card.addEventListener('click', () => {
      player.show(stories[idx].href, /* pageId */ null, {animate: false});
      document.body.classList.toggle('lightbox-open');
      lightboxEl.classList.remove('closed');
      card.classList.add('hidden');

      resetStyles();
      player.play();
    });

    card.addEventListener('mouseenter', () => {
      const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
      if (isMobile) return;
      showBackgroundCards(idx);
    });

    card.addEventListener('mouseleave', () => {
            const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
      if (isMobile) return;
      hideBackgroundCards(idx);
    });
  });
}

/**
 * Displays background cards on hover and pushes next cards.
 */
function showBackgroundCards(idx) {
  for (let i = idx + 1; i < cards.length; i++) {
    const savedTransform = cards[i].style.transform;
    const translateX = parseFloat(savedTransform.replace(/[^-?\d.]/g, '')) || 0;

    cards[i].style['transform'] = `translateX(${translateX + 24}px)`;
  }
}

/**
 * Hides background cards and resets next siblings to original position.
 */
function hideBackgroundCards(idx) {
  for (let i = idx + 1; i < cards.length; i++) {
    const savedTransform = cards[i].style.transform;
    const translateX = parseFloat(savedTransform.replace(/[^-?\d.]/g, '')) || 0;

    cards[i].style['transform'] = `translateX(${translateX - 24}px)`;
  }
}
